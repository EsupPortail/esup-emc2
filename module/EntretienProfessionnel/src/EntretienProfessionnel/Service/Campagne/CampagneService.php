<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\CampagneAgentStatut;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use Structure\Entity\Db\Structure;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Provider\Parametre\StructureParametres;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Service\StructureAgentForce\StructureAgentForceServiceAwareTrait;
use UnicaenApp\Form\Element\Date;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class CampagneService
{
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;
    use AgentForceSansObligationServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use StructureAgentForceServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use StructureServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Campagne $campagne): Campagne
    {
        $this->getObjectManager()->persist($campagne);
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    public function update(Campagne $campagne): Campagne
    {
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    public function historise(Campagne $campagne): Campagne
    {
        $campagne->historiser();
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    public function restore(Campagne $campagne): Campagne
    {
        $campagne->dehistoriser();
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    public function delete(Campagne $campagne): Campagne
    {
        $this->getObjectManager()->remove($campagne);
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Campagne::class)->createQueryBuilder('campagne')
            ->addSelect('precede')->leftJoin('campagne.precede', 'precede')
            /**
             * NB : Retirer, car si on a besoin des entretiens, on les récupère avec
             * @see EntretienProfessionnelService::getEntretiensProfessionnelsByCampagne
             */
//            ->addSelect('entretien')->leftJoin('campagne.entretiens', 'entretien');
//            $qb = EntretienProfessionnel::decorateWithEtats($qb, "entretien")
;
        return $qb;
    }

    /** @return Campagne[] */
    public function getCampagnes(bool $withhisto = false, string $champ = 'annee', string $ordre = 'DESC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.' . $champ, $ordre);
        if (!$withhisto) $qb = $qb->andWhere('campagne.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return array (id => string) */
    public function getCampagnesAsOptions(string $champ = 'annee', string $ordre = 'DESC'): array
    {
        $campagnes = $this->getCampagnes(false, $champ, $ordre);

        $array = [];
        foreach ($campagnes as $campagne) {
            $array[$campagne->getId()] = $campagne->getAnnee();
        }
        return $array;
    }

    public function getCampagne(?int $id): ?Campagne
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnelCampagne partage le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCampagne(AbstractActionController $controller, string $param = "campagne"): ?Campagne
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCampagne($id);
        return $result;
    }

    /** @return Campagne[] */
    public function getCampagnesActives(?DateTime $date = null): array
    {
        $qb = $this->createQueryBuilder()->andWhere('campagne.histoDestruction IS NULL');
        $qb = Campagne::decorateWithActif($qb, 'campagne', $date);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Campagne[] */
    public function getCampagnesFutures(?DateTime $date = null): array
    {
        $qb = $this->createQueryBuilder();
        $qb = Campagne::decorateWithNonCommence($qb, 'campagne', $date);
        $result = $qb->getQuery()->getResult();
        return $result;
    }


    public function getLastCampagne(?DateTime $date = null): ?Campagne
    {
        if ($date === null) $date = new DateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.dateFin < :date')
            ->setParameter('date', $date);
        $result = $qb->getQuery()->getResult();
        $last = null;
        /** @var Campagne $item */
        foreach ($result as $item) {
            if ($last === null or $item->getAnnee() > $last->getAnnee()) $last = $item;
        }
        return $last;
    }

    /**
     * @return EntretienProfessionnel[]
     * todo a mettre dans EPS
     */
    public function getEntretiensByCampagneAndEtats(Campagne $campagne, array $etats): array
    {
        $qb = $this->getObjectManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->andWhere('entretien.campagne in (:campagne)')->setParameter('campagne', $campagne)
            ->andWhere('entretien.histoDestruction IS NULL');
        $qb = EntretienProfessionnel::decorateWithEtats($qb, 'entretien', $etats);
        $result = $qb->getQuery()->getResult();

        $entretiens = [];
        /** @var EntretienProfessionnel $entretien */
        foreach ($result as $entretien) {
            $entretiens[$entretien->getAgent()->getId()][] = $entretien;
        }
        return $entretiens;
    }

//    /**
//     * @param Campagne $campagne
//     * @return EntretienProfessionnel[]
//     */
//    public function getEntretiensProfessionnels(Campagne $campagne): array
//    {
//        $entretiens = [];
//        foreach ($campagne->getEntretiensProfessionnels() as $entretien) {
//            if ($entretien->estNonHistorise()) {
//                $entretiens[$entretien->getAgent()->getId()][] = $entretien;
//            }
//        }
//        return $entretiens;
//    }


    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensEnAttenteResponsable(Campagne $campagne): array
    {
        $etats = [
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION),
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER),
        ];

        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
        return $entretiens;
    }

    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensEnAttenteAutorite(Campagne $campagne): array
    {
        $etats = [
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE),
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION),
        ];

        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
        return $entretiens;
    }

    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensEnAttenteAgent(Campagne $campagne): array
    {
        $etats = [
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE),
        ];

        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
        return $entretiens;
    }

//    /**
//     * @param Campagne $campagne
//     * @return EntretienProfessionnel[]
//     */
//    public function getEntretiensCompletes(Campagne $campagne): array
//    {
//        $etats = [
//            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT),
//        ];
//
//        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
//        return $entretiens;
//    }

    public function getAgentsEligibles(Campagne $campagne): array
    {
        $structureMere = $this->getStructureService()->getStructureMere();

        $agents = $this->getAgentService()->getAgentsWithDates($campagne->getDateDebut(), $campagne->getDateFin());
        [$obligatoires, $facultatifs, $raison] = $this->trierAgents($campagne, $agents);

        $sansObligations = array_map(function (AgentForceSansObligation $a) { return $a->getAgent(); }, $this->getAgentForceSansObligationService()->getAgentsForcesSansObligationByCampagne($campagne));
        $agentsFinales = [];
        foreach ($obligatoires as $agent) {
            $keep = true;
            foreach ($sansObligations as $sansObligation) {
                if ($agent === $sansObligation) {
                    $keep = false; break;
                }
            }
            if ($keep) $agentsFinales[$agent->getId()] = $agent;
        }

        return $agentsFinales;
    }

    /**
     * Retourne la campagne la plus à propos :
     * > Si il y a une campagne en cours la campagne en cours sinon la campagne la plus récente
     * > ultimement null si pas de campagne
     * */
    public function getBestCampagne(): ?Campagne {
        $campagnes = $this->getCampagnes();
        if (empty($campagnes)) return null;
        foreach ($campagnes as $campagne) {
            if ($campagne->estEnCours()) return $campagne;
        }
        return current($campagnes);
    }

    /** FACADE ********************************************************************************************************/

    public static function getAnneeScolaire(): string
    {
        $date = new DateTime();
        $annee = ((int)$date->format("Y"));
        $mois = ((int)$date->format("m"));

        if ($mois < 9) {
            $scolaire = ($annee - 1) . "/" . ($annee);
        } else {
            $scolaire = ($annee) . "/" . ($annee + 1);
        }
        return $scolaire;
    }

    /**
     * @param Campagne $campagne
     * @param Agent[] $agents
     * @param EntretienProfessionnel[] $entretiens
     * @param Structure[] $structures
     * @return array[]
     */
    public function trierAgents(Campagne $campagne, array $agents, ?array $entretiens = null, ?array $structures = null): array
    {
        $exclus = [];
        $obligatoires = [];
        $facultatifs = [];
        $raison = [];

        $parametres = $this->getParametreService()->getParametresByCategorieCode(EntretienProfessionnelParametres::TYPE);
        $strDatePriseDePoste = $campagne->getDateEnPoste()->format('d/m/Y');
        $strDateSituation = $campagne->getDateSituation()->format('d/m/Y');
        $dateSituation = $campagne->getDateSituation();

        // les fonctionnaires ne peuvent pas être exclus de la campagne
        // Seuls les agents avec le statut administratif peuvent passer un EP (TODO clarifier ici car cela n'est plus paramètrable)
        // ! MCF tout çà : ils sont fonctionnaires mais ne doivent plus passer d'EP
        $administratifs = $this->getAgentService()->getAdministratifs($agents, $dateSituation);
        // Si à la date de situation l'agent a le statut t_titulaire alors bypass de l'exclusion (non forcée)
        $fonctionnaires = $this->getAgentService()->getFonctionnaires($administratifs, $dateSituation);

        $a=1;
        $strStructure = implode(', ', array_map(function (Structure $s) { return $s->getLibelleLong(); }, $structures));

        foreach ($agents as $agent) {
            if (!isset($administratifs[$agent->getId()])) {
                $exclus[$agent->getId()] = $agent;
                $raison[$agent->getId()] = "<li>L'agent n'a pas le statut <code>administratif</code> à la date du ".$strDateSituation ."</li>";
            }
        }

        /** @var Agent $agent */
        foreach ($administratifs as $agent) {
            $raison[$agent->getId()] = "";

            // EXCLUSION ///////////////////////////////////////////////////////////////////////////////////////////////

            $estExclus = false;
            $estFiltre = false;

            if ($agent->isForceExclus($campagne, $structures)) {
                $exclus[$agent->getId()] = $agent;
                $raison[$agent->getId()] .= "Agent·e exclu·e de la campagne par une exception";
                continue;
            }

            if (!$agent->isForceAvecObligation($campagne, $structures)) {
                // Si non-fonctionnaire alors faire les tests d'exclusion
                if (!isset($fonctionnaires[$agent->getId()])) {

                    // Exclusion si l'agent n'est pas en poste (affectation/grade/statut) à  la date de prise de poste
                    // NB: peut-être détournée pour vérifier l'ancienneté
                    $result = $agent->hasAffectationActive($campagne->getDateEnPoste());
                    if ($result === false) {
                        $estExclus = true;
                        $raison[$agent->getId()] .= "Sans affectation à la date du " . $strDatePriseDePoste;
                    }
                    $result = $agent->hasGradeActif($campagne->getDateEnPoste());
                    if ($result === false) {
                        $estExclus = true;
                        $raison[$agent->getId()] .= "Sans grade à la date du " . $strDatePriseDePoste;
                    }
                    $result = $agent->hasStatutActif($campagne->getDateEnPoste());
                    if ($result === false) {
                        $estExclus = true;
                        $raison[$agent->getId()] .= "Sans statut à la date du " . $strDatePriseDePoste;
                    }

                    // Utilisation des paramètres d'exclusion
                    $result = $agent->isValideAffectation($parametres[EntretienProfessionnelParametres::TEMOIN_AFFECTATION_EXCLUS], $campagne->getDateSituation(), $structures, true);
                    if ($result[0] === true) {
                        $estExclus = true;
                        $explication = implode(", ", $result[1]);
                        $exclus[$agent->getId()] = $agent;
                        $raison[$agent->getId()] .= "<li>Affectation excluante  à la date du " . $strDateSituation . " (" . $explication . " dans les structures considérées [" . $strStructure . "])</li>";
                    }
                    $result = $agent->isValideCorps($parametres[EntretienProfessionnelParametres::TEMOIN_CORPS_EXCLUS], $campagne->getDateSituation());
                    if ($result[0] === true) {
                        $estExclus = true;
                        $explication = implode(", ", $result[1]);
                        $exclus[$agent->getId()] = $agent;
                        $raison[$agent->getId()] .= "Corps excluant à la date du " . $strDateSituation . " (" . $explication . ")";
                    }
                    $result = $agent->isValideStatut($parametres[EntretienProfessionnelParametres::TEMOIN_STATUT_EXCLUS], $campagne->getDateSituation());
                    if ($result[0] === true) {
                        $estExclus = true;
                        $explication = implode(", ", $result[1]);
                        $exclus[$agent->getId()] = $agent;
                        $raison[$agent->getId()] .= "Statut excluant à la date du " . $strDateSituation . " (" . $explication . ")";
                    }

                    // Exclusion EMPLOI-TYPE //
                    $result = $agent->isValideEmploiType($parametres[EntretienProfessionnelParametres::TEMOIN_EMPLOITYPE_EXCLUS], $campagne->getDateSituation());
                    if ($result[0] === true) {
                        $estExclus = true;
                        $explication = implode(", ", $result[1]);
                        $exclus[$agent->getId()] = $agent;
                        $raison[$agent->getId()] .= "Emploi-Type excluant  à la date du " . $strDatePriseDePoste . "(" . $explication . ")";
                    }
                }
            }

            if ($estExclus) {
                $exclus[$agent->getId()] = $agent;
            }

            if (!$estExclus) {
                // FILTRAGE ////////////////////////////////////////////////////////////////////////////////////////////////

                if ($agent->isForceSansObligation($campagne, $structures)) {
                    $estFiltre = true;
                    $raison[$agent->getId()] .= "<li>Agent·e forcé·e sans obligation dans la campagne par une exception</li>";
                }

                if (!$agent->isContratLong()) {
                    $estFiltre = true;
                    $raison[$agent->getId()] .= "<li>Sans 'contrat long'</li>";
                }

                $result = $agent->isValideAffectation($parametres[EntretienProfessionnelParametres::TEMOIN_AFFECTATION],$campagne->getDateSituation(), $structures);
                if ($result[0] === true)
                {
                    $estFiltre = true;
                    $explication = implode(", ",$result[1]);
                    $raison[$agent->getId()] = "<li>Affectation invalide  (à la date du ".$campagne->getDateEnPoste()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
                }

                $result = $agent->isValideStatut($parametres[EntretienProfessionnelParametres::TEMOIN_STATUT],$campagne->getDateEnPoste());
                if ($result[0] === true)
                {
                    $estFiltre = true;
                    $explication = implode(", ",$result[1]);
                    $raison[$agent->getId()] .= "<li>Statut invalide  (à la date du ".$campagne->getDateEnPoste()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
                }

                $result = $agent->isValideEmploiType($parametres[EntretienProfessionnelParametres::TEMOIN_EMPLOITYPE],$campagne->getDateEnPoste());
                if ($result[0] === true)
                {
                    $estFiltre = true;
                    $explication = implode(", ",$result[1]);
                    $raison[$agent->getId()] .= "<li>Emploi-type invalide  (à la date du ".$campagne->getDateEnPoste()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
                }

                $result = $agent->isValideCorps($parametres[EntretienProfessionnelParametres::TEMOIN_CORPS],$campagne->getDateEnPoste());
                if ($result[0] === true)
                {
                    $estFiltre = true;
                    $explication = implode(", ",$result[1]);
                    $raison[$agent->getId()] .= "<li>Corps invalide  (à la date du ".$campagne->getDateEnPoste()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
                }

                if ($estFiltre) {
                    $facultatifs[$agent->getId()] = $agent;
                } else {
                    $obligatoires[$agent->getId()] = $agent;
                }
            }
        }

        $forcages = $this->getAgentForceSansObligationService()->getAgentsForcesSansObligationByCampagneAndAgentsAndType($campagne, $agents, AgentForceSansObligation::FORCE_SANS_OBLIGATION);
        foreach ($forcages as $forcage) {
            $agent = $forcage->getAgent();
            if (isset($exclus[$agent->getId()]) OR isset($obligatoires[$agent->getId()])) {
                unset($exclus[$agent->getId()]);
                unset($obligatoires[$agent->getId()]);
                $facultatifs[$agent->getId()] = $agent;
                $raison[$agent->getId()] = '<li>Agent·e forcé·e sans obligation  dans la campagne par une exception</li>';
            }
        }

        $forcages = $this->getAgentForceSansObligationService()->getAgentsForcesSansObligationByCampagneAndAgentsAndType($campagne, $agents, AgentForceSansObligation::FORCE_AVEC_OBLIGATION);
        foreach ($forcages as $forcage) {
            $agent = $forcage->getAgent();
            if (isset($exclus[$agent->getId()]) OR isset($facultatifs[$agent->getId()])) {
                unset($exclus[$agent->getId()]);
                unset($facultatifs[$agent->getId()]);
                $obligatoires[$agent->getId()] = $agent;
                $raison[$agent->getId()] = '<li>Agent·e forcé·e avec obligation dans la campagne par une exception</li>';
            }
        }

        return [$obligatoires, $facultatifs, $raison, $exclus];



/**

            // FILTRAGE ////////////////////////////////////////////////////////////////////////////////////////////////



            if (!$agent->isContratLong()) {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Sans 'contrat long'</li>";
            }

            $result = $agent->isValideAffectation($parametres[EntretienProfessionnelParametres::TEMOIN_AFFECTATION],$campagne->getDateEnPoste(), $structures);
            if ($result[0] === true)
            {
                $kept = false;
                $explication = implode(", ",$result[1]);
                $raison[$agent->getId()] .= "<li>Affectation invalide  (à la date du ".$campagne->getDateEnPoste()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
            }

            // Filtrage STATUS //
            $result = $agent->isValideStatut($parametres[EntretienProfessionnelParametres::TEMOIN_STATUT],$campagne->getDateEnPoste());
            if ($result[0] === true)
            {
                $kept = false;
                $explication = implode(", ",$result[1]);
                $raison[$agent->getId()] .= "<li>Statut invalide  (à la date du ".$campagne->getDateEnPoste()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
            }

            // Filtrage EMPLOI-TYPE //
            $result = $agent->isValideEmploiType($parametres[EntretienProfessionnelParametres::TEMOIN_EMPLOITYPE],$campagne->getDateEnPoste());
            if ($result[0] === true)
            {
                $kept = false;
                $explication = implode(", ",$result[1]);
                $raison[$agent->getId()] .= "<li>Emploi-type invalide  (à la date du ".$campagne->getDateEnPoste()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
            }

            // Filtrage CORPS //
            $result = $agent->isValideCorps($parametres[EntretienProfessionnelParametres::TEMOIN_CORPS],$campagne->getDateEnPoste());
            if ($result[0] === true)
            {
                $kept = false;
                $explication = implode(", ",$result[1]);
                $raison[$agent->getId()] .= "<li>Corps invalide  (à la date du ".$campagne->getDateEnPoste()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
            }

            if ($agent->isForceAvecObligation($campagne, $structures)) {
                $raison[$agent->getId()] .= "<li>Forcé·e avec obligation</li>";
                $kept = true;
            }

            if ($kept) $obligatoires[$agent->getId()] = $agent; else $facultatifs[$agent->getId()] = $agent;
            $raison[$agent->getId()] .= "</ul>";
        }
        return [$obligatoires, $facultatifs, $raison, $exclus]; **/
    }

    public function refreshStatut(Campagne $campagne, Structure $structure): void
    {
        // calcul de la liste des structures
        $structures = $this->getStructureService()->getStructuresFilles($structure, true);

        // calcul de la liste des agents
        $agents = $this->getAgentService()->getAgentsByStructures($structures, $campagne->getDateDebut(), $campagne->getDateFin());
        $agentsForces = array_map(function (StructureAgentForce $agentForce) {
            return $agentForce->getAgent();
        }, $this->getStructureAgentForceService()->getStructureAgentsForcesByStructures($structures));
        foreach ($agentsForces as $agentForce) {
            if (!in_array($agentForce, $agents)) {
                $agents[] = $agentForce;
            }
        }
        [$obligatoires, $facultatifs, $raison, $exclus] = $this->trierAgents($campagne, $agents, [], $structures);

        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents, false, false);


        // purje ???
        $date = new DateTime();
        $statuts = [];
        foreach ($obligatoires as $obligatoire) {
            $statut = new CampagneAgentStatut();
            $statut->setCampagne($campagne);
            $statut->setAgent($obligatoire);
            $statut->setStructure($structure);
            $statut->setStatut(CampagneAgentStatut::OBLIGATOIRE);
            $statut->setRaison($raison[$obligatoire->getId()]??null);
            $statut->setRefreshDate($date);
            $statut->setEntretienProfessionnel($entretiens[$obligatoire->getId()]??null);
            $statuts[] = $statut;
        }
        foreach ($facultatifs as $facultatif) {
            $statut = new CampagneAgentStatut();
            $statut->setCampagne($campagne);
            $statut->setAgent($facultatif);
            $statut->setStructure($structure);
            $statut->setStatut(CampagneAgentStatut::FACULTATIF);
            $statut->setRaison($raison[$facultatif->getId()]??null);
            $statut->setRefreshDate($date);
            $statut->setEntretienProfessionnel($entretiens[$facultatif->getId()]??null);
            $statuts[] = $statut;
        }
        foreach ($exclus as $exclu) {
            $statut = new CampagneAgentStatut();
            $statut->setCampagne($campagne);
            $statut->setAgent($exclu);
            $statut->setStructure($structure);
            $statut->setStatut(CampagneAgentStatut::EXCLUS);
            $statut->setRaison($raison[$exclu->getId()]??null);
            $statut->setRefreshDate($date);
            $statut->setEntretienProfessionnel($entretiens[$exclu->getId()]??null);
            $statuts[] = $statut;
        }

        foreach ($statuts as $statut) {
            $this->getObjectManager()->persist($statut);
            $this->getObjectManager()->flush($statut);
        }
    }

    /** @return CampagneAgentStatut[] */
    public function getCampagneAgentStatut($campagne, $structure) : array
    {
        $qb = $this->getObjectManager()->getRepository(CampagneAgentStatut::class)->createQueryBuilder('cas')
            ->join('cas.structure', 'structure')->addSelect('structure')
            ->join('cas.campagne', 'campagne')->addSelect('campagne')
            ->join('cas.agent', 'agent')->addSelect('agent')
            ->leftjoin('cas.entretienProfessionnel', 'entretienProfessionnel')->addSelect('entretienProfessionnel')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;

    }

    public function computeProgressionStructure(Campagne $campagne, Structure $structure) : array
    {

    }

}