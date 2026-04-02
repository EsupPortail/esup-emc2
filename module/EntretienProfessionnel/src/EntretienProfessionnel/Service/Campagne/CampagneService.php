<?php

namespace EntretienProfessionnel\Service\Campagne;

use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Agent\Service\AgentStatut\AgentStatutServiceAwareTrait;
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
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Service\StructureAgentForce\StructureAgentForceServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class CampagneService
{
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use AgentForceSansObligationServiceAwareTrait;
    use AgentGradeServiceAwareTrait;
    use AgentStatutServiceAwareTrait;
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

        $now = new DateTime();
        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
        $filtered = [];
        foreach ($entretiens as $id => $entretiens_) {
            $entretiens_ = array_filter($entretiens_, function (EntretienProfessionnel $entretien) use ($now) {
                return $entretien->getDateFin() < $now;
            });
            if (!empty($entretiens_)) {
                $filtered[$id] = $entretiens_;
            }
        }
        return $filtered;
    }

    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensEnAttenteAutorite(Campagne $campagne): array
    {
        $etats = [
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
     * > S'il y a une campagne en cours la campagne en cours sinon la campagne la plus récente
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
        $inStructures = [];
        $outStructures = [];
        $exclus = [];
        $obligatoires = [];
        $facultatifs = [];
        $raison = [];

        $parametres = $this->getParametreService()->getParametresByCategorieCode(EntretienProfessionnelParametres::TYPE);
        $dateEnPoste = $campagne->getDateEnPoste();
        $strDatePriseDePoste = $campagne->getDateEnPoste()->format('d/m/Y');
        $dateSituation = $campagne->getDateSituation();
        $strDateSituation = $campagne->getDateSituation()->format('d/m/Y');


//        /** Tentative de réduction du nombre de requêtes */
//        $affectationDatePrisePoste = $this->getAgentAffectationService()->getAgentsAffectationsByAgentsAndDate($agents, $dateEnPoste);
//        $affectationDateSituation = $this->getAgentAffectationService()->getAgentsAffectationsByAgentsAndDate($agents, $dateSituation, $structures);


        $statutsDateSituation = $this->getAgentStatutService()->getAgentStatutsByAgents($agents, $dateSituation);
        $statutsDatePoste = $this->getAgentStatutService()->getAgentStatutsByAgents($agents, $dateEnPoste);
        $gradesDateSituation = $this->getAgentGradeService()->getAgentGradesByAgents($agents, $dateSituation);
        $gradesDatePoste = $this->getAgentGradeService()->getAgentGradesByAgents($agents, $dateEnPoste);


        // les fonctionnaires ne peuvent pas être exclus de la campagne
        // Seuls les agents avec le statut administratif peuvent passer un EP (TODO clarifier ici car cela n'est plus paramètrable)
        // ! MCF tout çà : ils sont fonctionnaires mais ne doivent plus passer d'EP
        $administratifs = $this->getAgentService()->getAdministratifs($agents, $dateSituation);
        // Si à la date de situation l'agent a le statut t_titulaire alors bypass de l'exclusion (non forcée)
        $fonctionnaires = $this->getAgentService()->getFonctionnaires($administratifs, $dateSituation);

        $strStructure = "Aucune structure";
        if ($structures !== null) {
            $strStructure = implode(', ', array_map(function (Structure $s) {
                return $s->getLibelleLong();
            }, $structures));
        }

//        // NB : Si on désactive ce truc, penser à modifier le foreach ci-après.
//        foreach ($agents as $agent) {
//            if (!isset($administratifs[$agent->getId()])) {
//                $exclus[$agent->getId()] = $agent;
//                $raison[$agent->getId()] = "<li>L'agent n'a pas le statut <code>administratif</code> à la date du ".$strDateSituation ."</li>";
//            }
//        }

//        foreach ($administratifs as $agent) {
        foreach ($agents as $agent) {
            $raison[$agent->getId()] = "";

            // EXCLUSION ///////////////////////////////////////////////////////////////////////////////////////////////
            $estExclus = false;
            $estFiltre = false;

//            if ($agent->isForceExclus($campagne, $structures)) {
//                $exclus[$agent->getId()] = $agent;
//                $raison[$agent->getId()] .= "<li>Agent·e exclu·e de la campagne par une exception</li>";
//                continue;
//            }

            if (!$agent->isForceAvecObligation($campagne, $structures)) {
//                $result = isset($affectationDateSituation[$agent->getId()]);
                $result = $agent->hasAffectationActive($dateSituation, $structures);
                if ($result === false) {
                    $estExclus = true;
                    $raison[$agent->getId()] .= "<li>Sans affectation dans les structures considérées à la date du " . $strDateSituation."</li>";
                }

                // Si non-fonctionnaire alors faire les tests d'exclusion
                if (!isset($fonctionnaires[$agent->getId()])) {
                    // Exclusion si l'agent n'est pas en poste (affectation/grade/statut) à la date de prise de poste
                    // NB : peut-être détournée pour vérifier l'ancienneté
//                    $result = isset($affectationDatePrisePoste[$agent->getId()]);
                    $result = $agent->hasAffectationActive($dateEnPoste);
                    if ($result === false) {
                        $estExclus = true;
                        $raison[$agent->getId()] .= "<li>Sans affectation à la date du " . $strDatePriseDePoste . "</li>";
                    }
//                    $result = $agent->hasGradeActif($dateEnPoste);
                    $result = isset($gradesDatePoste[$agent->getId()]);
                    if ($result === false) {
                        $estExclus = true;
                        $raison[$agent->getId()] .= "<li>Sans grade à la date du " . $strDatePriseDePoste . "</li>";
                    }
//                    $result = $agent->hasStatutActif($dateEnPoste);
                    $result = isset($statutsDatePoste[$agent->getId()]);
                    if ($result === false) {
                        $estExclus = true;
                        $raison[$agent->getId()] .= "<li>Sans statut à la date du " . $strDatePriseDePoste . "</li>";
                    }
                }

                // Utilisation des paramètres d'exclusion à la date de situation
                $result = $agent->isValideAffectation($parametres[EntretienProfessionnelParametres::TEMOIN_AFFECTATION_EXCLUS], $campagne->getDateSituation(), $structures, true);
                if ($result[0] === true) {
                    $estExclus = true;
                    $explication = implode(", ", $result[1]);
                    $exclus[$agent->getId()] = $agent;
                    $raison[$agent->getId()] .= "<li>Affectation excluante  à la date du " . $strDateSituation . " (" . $explication . " dans les structures considérées [" . $strStructure . "])</li>";
                }
                $result = $agent->isValideCorps($parametres[EntretienProfessionnelParametres::TEMOIN_CORPS_EXCLUS], $campagne->getDateSituation(), false, $gradesDateSituation[$agent->getId()]??[]);
                if ($result[0] === true) {
                    $estExclus = true;
                    $explication = implode(", ", $result[1]);
                    $exclus[$agent->getId()] = $agent;
                    $raison[$agent->getId()] .= "Corps excluant à la date du " . $strDateSituation . " (" . $explication . ")";
                }
                $result = $agent->isValideStatut($parametres[EntretienProfessionnelParametres::TEMOIN_STATUT_EXCLUS], $campagne->getDateSituation(), false, $statutsDateSituation[$agent->getId()]??[]);
                if ($result[0] === true) {
                    $estExclus = true;
                    $explication = implode(", ", $result[1]);
                    $exclus[$agent->getId()] = $agent;
                    $raison[$agent->getId()] .= "Statut excluant à la date du " . $strDateSituation . " (" . $explication . ")";
                }
                $result = $agent->isValideEmploiType($parametres[EntretienProfessionnelParametres::TEMOIN_EMPLOITYPE_EXCLUS], $campagne->getDateSituation());
                if ($result[0] === true) {
                    $estExclus = true;
                    $explication = implode(", ", $result[1]);
                    $exclus[$agent->getId()] = $agent;
                    $raison[$agent->getId()] .= "Emploi-Type excluant  à la date du " . $strDateSituation . "(" . $explication . ")";
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
                    $raison[$agent->getId()] = "<li>Affectation invalide (à la date du ".$campagne->getDateSituation()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
                }

                $result = $agent->isValideStatut($parametres[EntretienProfessionnelParametres::TEMOIN_STATUT],$campagne->getDateSituation(), false, $statutsDateSituation[$agent->getId()]??[]);
                if ($result[0] === true)
                {
                    $estFiltre = true;
                    $explication = implode(", ",$result[1]);
                    $raison[$agent->getId()] .= "<li>Statut invalide (à la date du ".$campagne->getDateSituation()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
                }

                $result = $agent->isValideEmploiType($parametres[EntretienProfessionnelParametres::TEMOIN_EMPLOITYPE],$campagne->getDateSituation());
                if ($result[0] === true)
                {
                    $estFiltre = true;
                    $explication = implode(", ",$result[1]);
                    $raison[$agent->getId()] .= "<li>Emploi-type invalide (à la date du ".$campagne->getDateSituation()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
                }

                $result = $agent->isValideCorps($parametres[EntretienProfessionnelParametres::TEMOIN_CORPS],$campagne->getDateSituation(), false, $grades[$agent->getId()]??[]);
                if ($result[0] === true)
                {
                    $estFiltre = true;
                    $explication = implode(", ",$result[1]);
                    $raison[$agent->getId()] .= "<li>Corps invalide (à la date du ".$campagne->getDateSituation()->format('d/m/y').") dans le cadre des entretiens professionnels (".$explication.")</li>";
                }

                if ($estFiltre) {
                    $facultatifs[$agent->getId()] = $agent;
                } else {
                    $obligatoires[$agent->getId()] = $agent;
                }
            }
        }

        $exceptions = $this->getAgentForceSansObligationService()->getAgentsForcesSansObligationByCampagneAndAgents($campagne, $agents);
        foreach ($exceptions[AgentForceSansObligation::FORCE_SANS_OBLIGATION] as $forcage) {
            $agent = $forcage->getAgent();
            if (isset($exclus[$agent->getId()]) OR isset($obligatoires[$agent->getId()])) {
                unset($exclus[$agent->getId()]);
                unset($obligatoires[$agent->getId()]);
                $facultatifs[$agent->getId()] = $agent;
                $raison[$agent->getId()] = '<li>Agent·e forcé·e sans obligation  dans la campagne par une exception</li>';
            }
        }
        foreach ($exceptions[AgentForceSansObligation::FORCE_EXCLUS] as $forcage) {
            $agent = $forcage->getAgent();
            if (isset($obligatoires[$agent->getId()]) OR isset($facultatifs[$agent->getId()])) {
                unset($obligatoires[$agent->getId()]);
                unset($facultatifs[$agent->getId()]);
                $exclus[$agent->getId()] = $agent;
                $raison[$agent->getId()] = '<li>Agent·e exclu·e dans la campagne par une exception</li>';
            }
        }
        foreach ($exceptions[AgentForceSansObligation::FORCE_AVEC_OBLIGATION] as $forcage) {
            $agent = $forcage->getAgent();
            if (isset($exclus[$agent->getId()]) OR isset($facultatifs[$agent->getId()])) {
                unset($exclus[$agent->getId()]);
                unset($facultatifs[$agent->getId()]);
                $obligatoires[$agent->getId()] = $agent;
                $raison[$agent->getId()] = '<li>Agent·e forcé·e avec obligation dans la campagne par une exception</li>';
            }
        }


        if ($entretiens !== null) {
            foreach ($entretiens as $entretien) {
                $agent = $entretien->getAgent();
                $affectations = $agent->getAffectationsActifs($entretien->getConvocation());
                $isInStructures = false;
                $autres = [];
                foreach ($affectations as $affectation) {
                    $autres[] = $affectation->getStructure()->getLibelleLong();
                    if (in_array($affectation->getStructure(), $structures)) {
                        $isInStructures = true;
                    }
                }
                if (!$isInStructures) {
                    unset($obligatoires[$agent->getId()]);
                    unset($facultatifs[$agent->getId()]);
                    unset($exclus[$agent->getId()]);
                    $outStructures[$agent->getId()] = $entretien;
                    $raison[$agent->getId()] = "Entretien dans une autre structure [" . implode(", ", $autres) . "]";
                } else {
                    $inStructures[$agent->getId()] = $entretien;
                }
            }
        }

        return [$obligatoires, $facultatifs, $raison, $exclus, $inStructures, $outStructures];
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
}