<?php

namespace Application\Service\FichePoste;

use Application\Entity\Db\Agent;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteApplicationRetiree;
use Application\Entity\Db\FicheTypeExterne;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\SpecificitePoste\SpecificitePosteServiceAwareTrait;
use Carriere\Entity\Db\NiveauEnveloppe;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Mission;
use FichePoste\Provider\Etat\FichePosteEtats;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;

class FichePosteService
{
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;
    use SpecificitePosteServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function create(FichePoste $fiche): FichePoste
    {
        $this->getObjectManager()->persist($fiche);
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function update(FichePoste $fiche): FichePoste
    {
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function historise(FichePoste $fiche): FichePoste
    {
        $fiche->historiser();
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function restore(FichePoste $fiche): FichePoste
    {
        $fiche->dehistoriser();
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function delete(FichePoste $fiche): FichePoste
    {
        $this->getObjectManager()->remove($fiche);
        $this->getObjectManager()->flush($fiche);
        return $fiche;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            // AGENT ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            //status de l'agent
            //            ->addSelect('statut')->leftJoin('agent.statuts', 'statut')
            //            ->addSelect('statut_structure')->leftJoin('statut.structure', 'statut_structure')
            //grade de l'agent
            //            ->addSelect('grade')->leftJoin('agent.grades', 'grade')
            //            ->addSelect('grade_structure')->leftJoin('grade.structure', 'grade_structure')
            //            ->addSelect('grade_grade')->leftJoin('grade.grade', 'grade_grade')
            //            ->addSelect('grade_corps')->leftJoin('grade.corps', 'grade_corps')
            //            ->addSelect('grade_correspondance')->leftJoin('grade.bap', 'grade_correspondance')
            //missions spécifiques
            //            ->addSelect('missionSpecifique')->leftJoin('agent.missionsSpecifiques', 'missionSpecifique')
            //            ->addSelect('structureM')->leftJoin('missionSpecifique.structure', 'structureM')
            //            ->addSelect('mission')->leftJoin('missionSpecifique.mission', 'mission')
            //            ->addSelect('mission_theme')->leftJoin('mission.theme', 'mission_theme')
            //            ->addSelect('mission_type')->leftJoin('mission.type', 'mission_type')


            //            ->addSelect('poste')->leftJoin('fiche.poste', 'poste')
            //            ->addSelect('specificite')->leftJoin('fiche.specificite', 'specificite')
            ->addSelect('externe')->leftJoin('fiche.fichesMetiers', 'externe')
            ->addSelect('fichemetier')->leftJoin('externe.ficheType', 'fichemetier')
            ->addSelect('metier')->leftJoin('fichemetier.metier', 'metier')
            ->addSelect('reference')->leftJoin('metier.references', 'reference')
            ->addSelect('referentiel')->leftJoin('reference.referentiel', 'referentiel');

        return $qb;
    }

    /** @return FichePoste[] */
    public function getFichesPostes(): array
    {
        $qb = $this->getObjectManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('externe')->leftJoin('fiche.fichesMetiers', 'externe')
            //        $qb = $this->createQueryBuilder()
            ->andWhere('fiche.histoDestruction IS NULL')
            ->orderBy('fiche.id', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichePoste(?int $id): ?FichePoste
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fiche.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FichePoste paratagent le même identifiant [" . $id . "]", $e);
        }
        return $result;
    }

    public function getRequestedFichePoste(AbstractActionController $controller, string $paramName = 'fiche-poste', bool $notNull = false): ?FichePoste
    {
        $id = $controller->params()->fromRoute($paramName);
        $fiche = $this->getFichePoste($id);
        if ($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [" . $id . "]");
        return $fiche;

    }

    /** Recupération des fiches de postes par agent ********************************************************************/

    /** @return FichePoste[] */
    public function getFichesPostesByAgent(Agent $agent): array
    {
        $qb = $this->getObjectManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->andWhere('fiche.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('fiche.id', 'ASC');
        /** @var FichePoste[] $result */
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichePosteByAgent(Agent $agent, ?DateTime $date = null): ?FichePoste
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->createQueryBuilder()
            ->andWhere('fiche.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('fiche.histoCreation <= :date')
            ->andWhere('fiche.histoDestruction IS NULL OR fiche.histoDestruction >= :date')
            ->setParameter('date', $date);
        $qb = FichePoste::decorateWithEtatsCodes($qb, 'fiche', [FichePosteEtats::ETAT_CODE_OK, FichePosteEtats::ETAT_CODE_SIGNEE]);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiches de poste remontées pour l'agent [" . $agent->getDenomination() . "] en date du [" . $date->format('d/m/Y') . "]", 0, $e);
        }
        return $result;
    }

    public function getFichePosteEnRedactionByAgent(Agent $agent, ?DateTime $date = null): ?FichePoste
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->createQueryBuilder()
            ->andWhere('fiche.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('fiche.histoCreation <= :date')
            ->andWhere('fiche.histoDestruction IS NULL OR fiche.histoDestruction >= :date')
            ->setParameter('date', $date);
        $qb = FichePoste::decorateWithEtatsCodes($qb, 'fiche', [FichePosteEtats::ETAT_CODE_REDACTION]);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiches de poste en rédaction de remontées pour l'agent [" . $agent->getDenomination() . "] en date du [" . $date->format('d/m/Y') . "]. Veuillez retirer les fiches excédentaires.", 0, $e);
        }
        return $result;
    }

    public function getFichePosteActiveByAgent(Agent $agent): ?FichePoste
    {
        return $this->getFichePosteByAgent($agent);
    }

    /** AUTRES *******************************************************************************************/

    public function getFichesPostesAsArray(): array
    {
        $params = ['agent_ids' => []];

        $sql = <<<EOS
select
    f.id, f.libelle as fiche_libelle, f.histo_destruction,
    a.c_individu AS agent_id, a.prenom, a.nom_usage,
    aa.id_orig,
    s.id as structure_id, s.libelle_court as structure,
    m.libelle_default as fiche_principale,
    et.id as etat,
    et.code as etat_code,
   (f.fin_validite IS NULL OR f.fin_validite > current_timestamp) as en_cours,
   f.fin_validite as fin_validite
from ficheposte f
left join agent a on f.agent = a.c_individu
left join agent_carriere_affectation aa on a.c_individu = aa.agent_id
left join structure s on aa.structure_id = s.id
left join ficheposte_fichemetier fte on f.id = fte.fiche_poste
left join fichemetier f2 on fte.fiche_type = f2.id
left join metier_metier m on m.id = f2.metier_id
left join ficheposte_etat fpe on f.id=fpe.ficheposte_id
left join unicaen_etat_instance ei on fpe.etat_id = ei.id
left join unicaen_etat_type et on ei.type_id = et.id
where (fte.principale = true OR fte IS NULL)
  and (                 aa IS NULL OR aa.t_principale = 'O' 
                    and aa.date_debut <= current_date 
                    AND (aa.date_fin IS NULL or aa.date_fin >= current_date) and aa.deleted_on is null)

EOS;

        try {
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, $params, ['agent_ids' => Connection::PARAM_INT_ARRAY]);
            $tmp = $res->fetchAllAssociative();
        } catch (DBA_Exception $e) {
            throw new RuntimeException("[DBA] Un problème est survenue lors de la récupération des agents d'un groupe de structures", 0, $e);
        } catch (DRV_Exception $e) {
            throw new RuntimeException("[DBV] Un problème est survenue lors de la récupération des agents d'un groupe de structures", 0, $e);
        }
        return $tmp;
    }

    /** FICHE TYPE EXTERNE ********************************************************************************************/

    public function createFicheTypeExterne(FicheTypeExterne $ficheTypeExterne): FicheTypeExterne
    {
        $this->getObjectManager()->persist($ficheTypeExterne);
        $this->getObjectManager()->flush($ficheTypeExterne);

        return $ficheTypeExterne;
    }

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function updateFicheTypeExterne(FicheTypeExterne $ficheTypeExterne): FicheTypeExterne
    {
        $this->getObjectManager()->flush($ficheTypeExterne);
        return $ficheTypeExterne;
    }

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function deleteFicheTypeExterne(FicheTypeExterne $ficheTypeExterne): FicheTypeExterne
    {
        $this->getObjectManager()->remove($ficheTypeExterne);
        $this->getObjectManager()->flush();
        return $ficheTypeExterne;
    }


    /**
     * @param integer $id
     * @return FicheTypeExterne
     */
    public function getFicheTypeExterne(int $id): FicheTypeExterne
    {
        $qb = $this->getObjectManager()->getRepository(FicheTypeExterne::class)->createQueryBuilder('externe')
            ->andWhere('externe.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieus FicheTypeExterne partagent le même identifiant [" . $id . "]", $e);
        }
        return $result;
    }

    public function getLastFichePoste(): FichePoste
    {
        $fiches = $this->getFichesPostes();
        return end($fiches);
    }

    /**
     * @param Structure[] $structures
     * @param boolean $sousstructure
     * @return FichePoste[]
     */
    public function getFichesPostesByStructures(array $structures = [], bool $sousstructure = true): array
    {
        $agentsStd = $this->getAgentService()->getAgentsByStructures($structures);
        $agentForcees = $this->getAgentService()->getAgentsForcesByStructures($structures);
        $agents = array_merge($agentsStd, $agentForcees);

        $qb = $this->getObjectManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->andWhere('fiche.agent in (:agents)')
            ->setParameter('agents', $agents)
            ->addSelect('agent')->join('fiche.agent', 'agent')
            ->addSelect('statut')->leftJoin('agent.statuts', 'statut')
            ->addSelect('grade')->leftJoin('agent.grades', 'grade')
            ->orderBy('agent.nomUsuel, agent.prenom');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @param boolean $sousstructure
     * @param Agent|null $agent
     * @return FichePoste[]
     */
    public function getFichesPostesByStructuresAndAgent(array $structures = [], bool $sousstructure = false, Agent $agent = null): array
    {
        $fiches = $this->getFichesPostesByStructures($structures, $sousstructure);
        $fiches = array_filter($fiches, function (FichePoste $a) use ($agent) {
            return (
                $a->estNonHistorise() and
                $a->isComplete() and
                $a->getEtatActif()->getType()->getCode() !== FichePosteEtats::ETAT_CODE_MASQUEE and
                ($a->getAgent()->getNiveauEnveloppe() !== null and
                    $agent->getNiveauEnveloppe() !== null and
                    NiveauEnveloppe::isCompatible($a->getAgent()->getNiveauEnveloppe(), $agent->getNiveauEnveloppe())));
        });
        return $fiches;

    }

    /**
     * @param Structure $structure
     * @param boolean $sousstructure
     * @return FichePoste[]
     */
    public function getFichesPostesSansAgentByStructure(Structure $structure, bool $sousstructure = false): array
    {
        $qb = $this->getObjectManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('poste')->join('fiche.poste', 'poste')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('structure')->join('poste.structure', 'structure')
            ->andWhere('agent.id IS NULL')
            ->orderBy('poste.numeroPoste');

        if ($sousstructure === false) {
            $qb = $qb
                ->andWhere('structure = :structure')
                ->setParameter('structure', $structure);
        }
        if ($sousstructure === true) {
            $qb = $qb
                ->andWhere('structure = :structure OR structure.parent = :structure')
                ->setParameter('structure', $structure);
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * Calcul du set d'applications associées à une fiche de poste et/ou une fiche metier "externe".
     * Va tenir compte des applications conservées (ou retirées par l'auteur de la fiche de poste)
     * @param FichePoste $ficheposte
     * @param FicheMetier $fichemetier
     * @return array
     */
    public function getApplicationsAssocieesFicheMetier(FichePoste $ficheposte, FicheMetier $fichemetier)
    {

        //provenant de la fiche metier
        $applications = [];
        foreach ($ficheposte->getFichesMetiers() as $fichemetiertype) {
            if ($fichemetiertype->getFicheType() === $fichemetier) {

                //provenant de la fiche metier
                foreach ($fichemetier->getApplicationListe() as $applicationElement) {
                    $application = $applicationElement->getApplication();
                    if (!isset($applications[$application->getId()])) {
                        $applications[$application->getId()] = [
                            'entity' => $application,
                            'display' => true,
                            'raisons' => [['Fiche métier', $fichemetier]]
                        ];
                    } else {
                        $applications[$application->getId()]['raisons'][] = ['FicheMetier', $fichemetier];
                    }
                }

                //provenant des activités
                $keptActivites = explode(";", $fichemetiertype->getActivites());
            }
        }

        $retirees = $ficheposte->getApplicationsRetirees();
        /** @var FicheposteApplicationRetiree $conservee */
        foreach ($retirees as $retiree) {
            if ($retiree->getHistoDestruction() === null) $applications[$retiree->getApplication()->getId()]['display'] = false;
        }

        return $applications;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostesSansAgent(): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.id is NULL')
            ->andWhere('poste.id is NOT NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostesSansPoste(): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.id is NULL')
            ->andWhere('agent.id is NOT NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostesSansAgentEtPoste(): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.id is NULL')
            ->andWhere('agent.id is NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichesPostesAvecAgentEtPoste(): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.id is NOT NULL')
            ->andWhere('agent.id is NOT NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** Dictionnaires associés aux fiches de poste ********************************************************************/

    /**
     * @param FichePoste $fiche
     * @return array
     */
    public function getActivitesDictionnaires(FichePoste $fiche): array
    {

        $dictionnaire = [];

        /** Recuperation des fiches metiers */
        foreach ($fiche->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $activitesId = explode(';', $ficheTypeExterne->getActivites());
            foreach ($ficheMetier->getMissions() as $metierTypeActivite) {
                $id = $metierTypeActivite->getMission()->getId();
                $dictionnaire[$id]["object"] = $metierTypeActivite;
                $dictionnaire[$id]["conserve"] = (in_array($id, $activitesId));
            }
        }

        return $dictionnaire;
    }

    public function getApplicationsDictionnaires(FichePoste $fiche): array
    {
        $dictionnaire = [];

        /**
         * @var FicheMetier[] $ficheMetier
         * @var Mission[] $missions
         */
        $fichesMetiers = [];
        $missions = [];

        /** Recuperation des fiches metiers */
        foreach ($fiche->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $fichesMetiers[] = $ficheMetier;
            $activitesId = explode(';', $ficheTypeExterne->getActivites());
            foreach ($ficheMetier->getMissions() as $metierTypeActivite) {
                $id = $metierTypeActivite->getMission()->getId();
                if (in_array($id, $activitesId)) {
                    $missions[] = $metierTypeActivite->getMission();
                }
            }
        }

        foreach ($fichesMetiers as $ficheMetier) {
            foreach ($ficheMetier->getApplicationListe() as $applicationElement) {
                $application = $applicationElement->getApplication();
                $dictionnaire[$application->getId()]["entite"] = $applicationElement;
                $dictionnaire[$application->getId()]["raison"][] = $ficheMetier;
                $dictionnaire[$application->getId()]["conserve"] = true;
            }
        }

        $retirees = $fiche->getApplicationsRetirees();
        foreach ($retirees as $retiree) {
            $dictionnaire[$retiree->getApplication()->getId()]["conserve"] = false;
            $dictionnaire[$retiree->getApplication()->getId()]["entite"] = $retiree->getApplication();
        }

        return $dictionnaire;
    }

    /**
     * @param FichePoste $fiche
     * @return array
     */
    public function getCompetencesDictionnaires(FichePoste $fiche): array
    {
        $dictionnaire = [];

        /**
         * @var FicheMetier[] $ficheMetier
         * @var Mission[] $missions
         */
        $fichesMetiers = [];
        $missions = [];

        /** Recuperation des fiches metiers */
        foreach ($fiche->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $fichesMetiers[] = $ficheMetier;
            $activitesId = explode(';', $ficheTypeExterne->getActivites());
            foreach ($ficheMetier->getMissions() as $metierTypeActivite) {
                $id = $metierTypeActivite->getMission()->getId();
                if (in_array($id, $activitesId)) {
                    $missions[] = $metierTypeActivite->getMission();
                }
            }
        }

        foreach ($fichesMetiers as $ficheMetier) {
            foreach ($ficheMetier->getCompetenceListe() as $competenceElement) {
                $competence = $competenceElement->getCompetence();
                //$dictionnaire[$competence->getId()]["object"] = $competence;
                $dictionnaire[$competence->getId()]["entite"] = $competenceElement;
                $dictionnaire[$competence->getId()]["raison"][] = $ficheMetier;
                $dictionnaire[$competence->getId()]["conserve"] = true;
            }
        }

        $retirees = $fiche->getCompetencesRetirees();
        foreach ($retirees as $retiree) {
            $dictionnaire[$retiree->getCompetence()->getId()]["entite"] = $retiree->getCompetence();
            $dictionnaire[$retiree->getCompetence()->getId()]["conserve"] = false;
        }

        return $dictionnaire;
    }

    /**
     * @param Structure[] $structures
     * @param bool $soustructure
     * @return array
     */
    public function getFichesPostesByStructuresAsOptions(array $structures, bool $soustructure): array
    {
        $fichespostes = $this->getFichesPostesByStructures($structures, $soustructure);
        $options = [];
        foreach ($fichespostes as $ficheposte) {
            $label = $ficheposte->getLibelleMetierPrincipal();
            if ($ficheposte->getAgent() !== null) $label .= " (" . $ficheposte->getAgent()->getDenomination() . ")";
            $options[$ficheposte->getId()] = $label;
        }

        return $options;
    }

    /**
     * @param Structure[] $structures
     * @param bool $soustructure
     * @return array
     */
    public function getFichesPostesRecrutementByStructuresAsOptions(array $structures, bool $soustructure): array
    {
        $fichespostes = [];
        foreach ($structures as $structure) {
            $fichespostes_tmp = $structure->getFichesPostesRecrutements();
            foreach ($fichespostes_tmp as $ficheposte) {
                $fichespostes[] = $ficheposte;
            }
        }
        $options = [];
        foreach ($fichespostes as $ficheposte) {
            $label = $ficheposte->getLibelleMetierPrincipal();
            if ($ficheposte->getAgent() !== null) $label .= " (" . $ficheposte->getAgent()->getDenomination() . ")";
            $options[$ficheposte->getId()] = $label;
        }

        return $options;
    }

    /**
     * @param FichePoste $fiche
     * @param bool $dupliquer_specificite
     * @return FichePoste
     */
    public function clonerFichePoste(FichePoste $fiche, bool $dupliquer_specificite): FichePoste
    {
        $nouvelleFiche = new FichePoste();
        $nouvelleFiche->setLibelle($fiche->getLibelle());
        $nouvelleFiche = $this->create($nouvelleFiche);

        if ($fiche->getSpecificite()) {
            if ($dupliquer_specificite) {
                $specifite = $fiche->getSpecificite()->clone_it();
                $this->getSpecificitePosteService()->create($specifite);
                $nouvelleFiche->setSpecificite($specifite);

            }
        }

//        $etat = $this->getEtatTypeService()->getEtatTypeByCode(FichePosteEtats::ETAT_CODE_REDACTION);
//        $nouvelleFiche->addEtat($etat);
        $nouvelleFiche = $this->update($nouvelleFiche);


        //dupliquer fiche metier externe
        foreach ($fiche->getFichesMetiers() as $ficheMetierExterne) {
            $nouvelleFicheMetier = $ficheMetierExterne->clone_it();
            $nouvelleFicheMetier->setFichePoste($nouvelleFiche);
            $this->createFicheTypeExterne($nouvelleFicheMetier);
        }

        return $nouvelleFiche;
    }

    /**
     * @param Agent|null $agent
     * @return FichePoste[]
     */
    public function getFichesPostesSigneesActives(?Agent $agent): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fiche.finValidite IS NULL')
            ->andWhere('fiche.agent =  :agent')
            ->setParameter('agent', $agent);
        $qb = FichePoste::decorateWithEtatsCodes($qb, 'fiche', [FichePosteEtats::ETAT_CODE_SIGNEE]);
        $result = $qb->getQuery()->getResult();
        return $result;

    }

    public function getFichesPostesByFicheMetier(?FicheMetier $fichemetier, bool $withFini = false, bool $withHisto = false)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fichemetier.id = :id')->setParameter('id', $fichemetier->getId());
        if (!$withFini) $qb = $qb->andWhere('fiche.finValidite IS NULL or fiche.finValidite > :now')->setParameter('now', new DateTime());
        if (!$withHisto) $qb = $qb->andWhere('fiche.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}