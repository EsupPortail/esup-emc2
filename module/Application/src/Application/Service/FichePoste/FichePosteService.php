<?php

namespace Application\Service\FichePoste;

use Application\Entity\Db\Activite;
use Application\Entity\Db\Agent;
use Application\Entity\Db\DomaineRepartition;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteApplicationRetiree;
use Application\Entity\Db\FicheTypeExterne;
use Application\Entity\Db\Structure;
use Application\Service\GestionEntiteHistorisationTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class FichePosteService {
    use GestionEntiteHistorisationTrait;
    use StructureServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function create(FichePoste $fiche)
    {
        $this->createFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function update(FichePoste $fiche)
    {
        $this->updateFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function historise(FichePoste $fiche)
    {
        $this->historiserFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function restore(FichePoste $fiche)
    {
        $this->restoreFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function delete(FichePoste $fiche)
    {
        $this->deleteFromTrait($fiche);
        return $fiche;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            // AGENT ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            //status de l'agent
            ->addSelect('statut')->leftJoin('agent.statuts', 'statut')
            ->addSelect('statut_structure')->leftJoin('statut.structure', 'statut_structure')
            //grade de l'agent
            ->addSelect('grade')->leftJoin('agent.grades', 'grade')
            ->addSelect('grade_structure')->leftJoin('grade.structure', 'grade_structure')
            ->addSelect('grade_grade')->leftJoin('grade.grade', 'grade_grade')
            ->addSelect('grade_corps')->leftJoin('grade.corps', 'grade_corps')
            ->addSelect('grade_correspondance')->leftJoin('grade.bap', 'grade_correspondance')
            //missions spécifiques
            ->addSelect('missionSpecifique')->leftJoin('agent.missionsSpecifiques', 'missionSpecifique')
            ->addSelect('structureM')->leftJoin('missionSpecifique.structure', 'structureM')
            ->addSelect('mission')->leftJoin('missionSpecifique.mission', 'mission')
            ->addSelect('mission_theme')->leftJoin('mission.theme', 'mission_theme')
            ->addSelect('mission_type')->leftJoin('mission.type', 'mission_type')



            ->addSelect('poste')->leftJoin('fiche.poste', 'poste')
            ->addSelect('specificite')->leftJoin('fiche.specificite', 'specificite')
            ->addSelect('externe')->leftJoin('fiche.fichesMetiers', 'externe')
            ->addSelect('fichemetier')->leftJoin('externe.ficheType', 'fichemetier')
            ->addSelect('metier')->leftJoin('fichemetier.metier', 'metier')
            ->addSelect('reference')->leftJoin('metier.references', 'reference')
            ->addSelect('referentiel')->leftJoin('reference.referentiel', 'referentiel')
            ;
        return $qb;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostes()
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('externe')->leftJoin('fiche.fichesMetiers', 'externe')
//        $qb = $this->createQueryBuilder()
            ->andWhere('fiche.histoDestruction IS NULL')
            ->orderBy('fiche.id', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FichePoste
     */
    public function getFichePoste(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fiche.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FichePoste paratagent le même identifiant [".$id."]",$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @param bool $notNull
     * @return FichePoste
     */
    public function getRequestedFichePoste(AbstractActionController $controller, string $paramName = 'fiche-poste', bool $notNull = false)
    {
        $id = $controller->params()->fromRoute($paramName);
        $fiche = $this->getFichePoste($id);
        if($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [".$id."]");
        return $fiche;

    }

    /** FICHE TYPE EXTERNE ********************************************************************************************/

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function createFicheTypeExterne(FicheTypeExterne $ficheTypeExterne)
    {
        try {
            $this->getEntityManager()->persist($ficheTypeExterne);
            $this->getEntityManager()->flush($ficheTypeExterne);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'ajout d'une fiche metier externe.", $e);
        }

        $domaines = $ficheTypeExterne->getFicheType()->getMetier()->getDomaines();
        try {
            foreach ($domaines as $domaine) {
                $repartition = new DomaineRepartition();
                $repartition->setFicheMetierExterne($ficheTypeExterne);
                $repartition->setDomaine($domaine);
                $repartition->setQuotite(100);
                $this->getEntityManager()->persist($repartition);
                $this->getEntityManager()->flush($repartition);
            }
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'ajout des DomaineRepartition.", $e);
        }

        return $ficheTypeExterne;
    }

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function updateFicheTypeExterne(FicheTypeExterne $ficheTypeExterne)
    {
        try {
            $this->getEntityManager()->flush($ficheTypeExterne);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour d'une fiche metier externe.", $e);
        }
        return $ficheTypeExterne;
    }

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function deleteFicheTypeExterne(FicheTypeExterne $ficheTypeExterne)
    {
        try {
            $this->getEntityManager()->remove($ficheTypeExterne);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors du retrait d'une fiche metier externe.", $e);
        }
        return $ficheTypeExterne;
    }


    /**
     * @param integer $id
     * @return FicheTypeExterne
     */
    public function getFicheTypeExterne(int $id)
    {
        $qb = $this->getEntityManager()->getRepository(FicheTypeExterne::class)->createQueryBuilder('externe')
            ->andWhere('externe.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieus FicheTypeExterne partagent le même identifiant [".$id."]",$e);
        }
        return $result;
    }

    /**
     * @return FichePoste
     */
    public function getLastFichePoste()
    {
        $fiches = $this->getFichesPostes();
        return end($fiches);
    }

    /**
     * @param Structure[] $structures
     * @param boolean $sousstructure
     * @return FichePoste[]
     */
    public function getFichesPostesByStructures(array $structures = [], bool $sousstructure = true)
    {
        try {
            $today = new DateTime();
            //$noEnd = DateTime::createFromFormat('d/m/Y H:i:s', '31/12/1999 00:00:00');
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la création des dates");
        }

        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('agent')->join('fiche.agent', 'agent')
            ->addSelect('poste')->leftJoin('fiche.poste', 'poste')
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->addSelect('grade')->join('agent.grades', 'grade')
            ->addSelect('structure')->join('grade.structure', 'structure')
            ->addSelect('fichemetier')->leftJoin('fiche.fichesMetiers', 'fichemetier')
            ->addSelect('affectation')->join('agent.affectations', 'affectation')
            ->andWhere('statut.fin >= :today OR statut.fin IS NULL')
            ->andWhere('statut.dispo = :false')
            ->andWhere('statut.enseignant = :false AND statut.chercheur = :false AND statut.etudiant = :false AND statut.retraite = :false')
            //->andWhere('statut.administratif = :true')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
            ->andWhere('affectation.dateFin >= :today OR affectation.dateFin IS NULL')
            ->andWhere('affectation.principale = :true')

            ->setParameter('today', $today)
            //->setParameter('noEnd', $noEnd)
            ->setParameter('true', 'O')
                ->setParameter('false', 'N')
            ->orderBy('agent.nomUsuel, agent.prenom')
        ;

        if ($sousstructure) {
            $qb = $qb
                ->andWhere('statut.structure IN (:structures)')
                ->setParameter('structures', $structures);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @param boolean $sousstructure
     * @param Agent|null $agent
     * @return FichePoste[]
     */
    public function getFichesPostesByStructuresAndAgent(array $structures = [], bool $sousstructure = false, Agent $agent = null)
    {
        $fiches = $this->getFichesPostesByStructures($structures, $sousstructure);
        $niveau = $agent->getMeilleurNiveau();

        if ($niveau === null) return $fiches;

        $result = [];
        foreach ($fiches as $fiche) {
            if ($fiche->isComplete()) {
                $niveauA = $fiche->getAgent()->getMeilleurNiveau();
                if ($niveauA === null or $niveauA >= ($niveau - 1)) $result[] = $fiche;
            }
        }

        return $result;
    }

    /**
     * @param Structure $structure
     * @param boolean $sousstructure
     * @return FichePoste[]
     */
    public function getFichesPostesSansAgentByStructure(Structure $structure, bool $sousstructure = false)
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('poste')->join('fiche.poste', 'poste')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('structure')->join('poste.structure', 'structure')
            ->andWhere('agent.id IS NULL')
            ->orderBy('poste.numeroPoste')
        ;

        if ($structure !== null && $sousstructure === false) {
            $qb = $qb
                ->andWhere('structure = :structure')
                ->setParameter('structure', $structure);
        }
        if ($structure !== null && $sousstructure === true) {
            $qb = $qb
                ->andWhere('structure = :structure OR structure.parent = :structure')
                ->setParameter('structure', $structure);
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * Calcul du set d'applications associées à une fiche de poste et/ou une fiche metier "externe".
     * Va tenir compte de applications conservées (ou retirées par l'auteur de la fiche de poste)
     * @param FichePoste $ficheposte
     * @param FicheMetier $fichemetier
     * @return array
     */
    public function getApplicationsAssocieesFicheMetier(FichePoste $ficheposte, FicheMetier $fichemetier) {

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
                            'raisons' => [[ 'Fiche métier' , $fichemetier]]
                        ];
                    } else {
                        $applications[$application->getId()]['raisons'][] = [ 'FicheMetier' , $fichemetier];
                    }
                }

                //provenant des activités
                $keptActivites = explode(";", $fichemetiertype->getActivites());
                foreach ($fichemetier->getActivites() as $activite) {
                    if (array_search($activite->getId(), $keptActivites) !== false) {
                        foreach ($activite->getActivite()->getApplicationListe() as $applicationElement) {
                            $application = $applicationElement->getApplication();
                            if (!isset($applications[$application->getId()])) {
                                $applications[$application->getId()] = [
                                    'entity' => $application,
                                    'display' => true,
                                    'raisons' => [[ 'Activité' , $activite->getActivite()]]
                                ];
                            } else {
                                $applications[$application->getId()]['raisons'][] = [ 'Activité' , $activite->getActivite()];
                            }
                        }
                    }
                }

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
    public function getFichesPostesSansAgent() {
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.id is NULL')
            ->andWhere('poste.id is NOT NULL')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostesSansPoste()
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
    public function getFichesPostesSansAgentEtPoste()
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.id is NULL')
            ->andWhere('agent.id is NULL')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichesPostesAvecAgentEtPoste()
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.id is NOT NULL')
            ->andWhere('agent.id is NOT NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function isGererPar(FichePoste $fiche, User $user)
    {
        if ($fiche->getPoste()) {
            $structure = $fiche->getPoste()->getStructure();
            if ($this->getStructureService()->isGestionnaire($structure, $user)) return true;
        }
        if ($fiche->getPoste()) {
            $structure = $fiche->getPoste()->getStructure();
            if ($this->getStructureService()->isResponsable($structure, $user)) return true;
        }
        if ($fiche->getAgent()) {
            foreach ($fiche->getAgent()->getAffectationsActifs() as $grade) {
                $structure = $grade->getStructure();
                if ($this->getStructureService()->isGestionnaire($structure, $user)) return true;
            }
        }
        return false;
    }

    /** Dictionnaires associés aux fiches de poste ********************************************************************/

    /**
     * @param FichePoste $fiche
     * @return array
     */
    public function getActivitesDictionnaires(FichePoste $fiche) {

        $dictionnaire = [];

        /** Recuperation des fiches metiers */
        foreach ($fiche->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $fichesMetiers[] = $ficheMetier;
            $activitesId = explode(';',$ficheTypeExterne->getActivites());
            foreach ($ficheMetier->getActivites() as $metierTypeActivite) {
                $id = $metierTypeActivite->getActivite()->getId();
                $dictionnaire[$id]["object"] = $metierTypeActivite;
                $dictionnaire[$id]["conserve"] = (array_search($id, $activitesId) !== false);
            }
        }

        return $dictionnaire;
    }

    /**
     * @param FichePoste $fiche
     * @param DateTime $date
     * @return array
     */
    public function getApplicationsDictionnaires(FichePoste $fiche)
    {
        $dictionnaire = [];

        /**
         * @var FicheMetier[] $ficheMetier
         * @var Activite[] $activites
         */
        $fichesMetiers = [];
        $activites = [];

        /** Recuperation des fiches metiers */
        foreach ($fiche->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $fichesMetiers[] = $ficheMetier;
            $activitesId = explode(';',$ficheTypeExterne->getActivites());
            foreach ($ficheMetier->getActivites() as $metierTypeActivite) {
                $id = $metierTypeActivite->getActivite()->getId();
                if (array_search($id, $activitesId) !== false) {
                    $activites[] = $metierTypeActivite->getActivite();
                }
            }
        }

        foreach ($fichesMetiers as $ficheMetier) {
            foreach ($ficheMetier->getApplicationListe() as $applicationElement) {
                $application = $applicationElement->getApplication();
                $dictionnaire[$application->getId()]["entite"] = $application;
                $dictionnaire[$application->getId()]["raison"][] = $ficheMetier;
                $dictionnaire[$application->getId()]["conserve"] = true;
            }
        }

        foreach ($activites as $activite) {
            foreach ($activite->getApplicationListe() as $applicationElement) {
                $application = $applicationElement->getApplication();
                $dictionnaire[$application->getId()]["entite"] = $application;
                $dictionnaire[$application->getId()]["raison"][] = $activite;
                $dictionnaire[$application->getId()]["conserve"] = true;
            }
        }

        $retirees = $fiche->getApplicationsRetirees();
        foreach ($retirees as $retiree) {
            $dictionnaire[$retiree->getApplication()->getId()]["conserve"] = false;
        }

        return $dictionnaire;
    }

    /**
     * @param FichePoste $fiche
     * @param DateTime $date
     * @return array
     */
    public function getFormationsDictionnaires(FichePoste $fiche)
    {
        $dictionnaire = [];

        /**
         * @var FicheMetier[] $ficheMetier
         * @var Activite[] $activites
         */
        $fichesMetiers = [];
        $activites = [];

        /** Recuperation des fiches metiers */
        foreach ($fiche->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $fichesMetiers[] = $ficheMetier;
            $activitesId = explode(';',$ficheTypeExterne->getActivites());
            foreach ($ficheMetier->getActivites() as $metierTypeActivite) {
                $id = $metierTypeActivite->getActivite()->getId();
                if (array_search($id, $activitesId) !== false) {
                    $activites[] = $metierTypeActivite->getActivite();
                }
            }
        }

        foreach ($fichesMetiers as $ficheMetier) {
            foreach ($ficheMetier->getFormations() as $formation) {
                $dictionnaire[$formation->getId()]["object"] = $formation;
                $dictionnaire[$formation->getId()]["raison"][] = $ficheMetier;
                $dictionnaire[$formation->getId()]["conserve"] = true;
            }
        }

        foreach ($activites as $activite) {
            foreach ($activite->getFormations() as $formation) {
                $dictionnaire[$formation->getId()]["object"] = $formation;
                $dictionnaire[$formation->getId()]["raison"][] = $activite;
                $dictionnaire[$formation->getId()]["conserve"] = true;
            }
        }

        return $dictionnaire;
    }

    /**
     * @param FichePoste $fiche
     * @param DateTime $date
     * @return array
     */
    public function getCompetencesDictionnaires(FichePoste $fiche)
    {
        $dictionnaire = [];

        /**
         * @var FicheMetier[] $ficheMetier
         * @var Activite[] $activites
         */
        $fichesMetiers = [];
        $activites = [];

        /** Recuperation des fiches metiers */
        foreach ($fiche->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $fichesMetiers[] = $ficheMetier;
            $activitesId = explode(';',$ficheTypeExterne->getActivites());
            foreach ($ficheMetier->getActivites() as $metierTypeActivite) {
                $id = $metierTypeActivite->getActivite()->getId();
                if (array_search($id, $activitesId) !== false) {
                    $activites[] = $metierTypeActivite->getActivite();
                }
            }
        }

        foreach ($fichesMetiers as $ficheMetier) {
            foreach ($ficheMetier->getCompetenceListe() as $competenceElement) {
                $competence = $competenceElement->getCompetence();
                //$dictionnaire[$competence->getId()]["object"] = $competence;
                $dictionnaire[$competence->getId()]["entite"] = $competence;
                $dictionnaire[$competence->getId()]["raison"][] = $ficheMetier;
                $dictionnaire[$competence->getId()]["conserve"] = true;
            }
        }

        foreach ($activites as $activite) {
            foreach ($activite->getCompetenceListe() as $competenceElement) {
                $competence = $competenceElement->getCompetence();
                $dictionnaire[$competence->getId()]["object"] = $competence;
                $dictionnaire[$competence->getId()]["raison"][] = $activite;
                $dictionnaire[$competence->getId()]["conserve"] = true;
            }
        }

        $retirees = $fiche->getCompetencesRetirees();
        foreach ($retirees as $retiree) {
            $dictionnaire[$retiree->getCompetence()->getId()]["conserve"] = false;
        }

        return $dictionnaire;
    }

    /**
     * @param Agent $agent
     * @return FichePoste
     */
    public function getFichePosteByAgent(Agent $agent)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fiche.agent = :agent')
            ->setParameter('agent',$agent)
            ->andWhere('fiche.histoDestruction IS NULL')
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiche de postes actives associés à l'agent [".$agent->getId()."|".$agent->getDenomination()."]",0,$e);
        }
        return $result;
    }

    public function updateRepatitions(FicheTypeExterne $fichetype, $data)
    {
        /** @var DomaineRepartition[] $repartitions */
        $repartitions = $fichetype->getDomaineRepartitions()->toArray();
        foreach ($repartitions as $repartition) {
            $domaineId = $repartition->getDomaine()->getId();
            $value = isset($data[$domaineId])?$data[$domaineId]:0;

            $repartition->setQuotite($value);
        }

        try {
            foreach ($repartitions as $repartition) {
                $this->getEntityManager()->flush($repartition);
            }
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement de la répartition.",0,$e);
        }
    }

    public function getFichesPostesByStructuresAsOptions(array $structures, bool $soustructure)
    {
        $fichespostes = $this->getFichesPostesByStructures($structures, $soustructure);
        $options = [];
        foreach ($fichespostes as $ficheposte) {
            $label = $ficheposte->getLibelleMetierPrincipal();
            if ($ficheposte->getAgent() !== null) $label .= " (".$ficheposte->getAgent()->getDenomination().")";
            $options[$ficheposte->getId()] = $label;
        }
        return $options;
    }
}