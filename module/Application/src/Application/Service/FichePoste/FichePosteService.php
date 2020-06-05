<?php

namespace Application\Service\FichePoste;

use Application\Entity\Db\Activite;
use Application\Entity\Db\Agent;
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
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;
    use StructureServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function create($fiche)
    {
        $this->createFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function update($fiche)
    {
        $this->updateFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function historise($fiche)
    {
        $this->historiserFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function restore($fiche)
    {
        $this->restoreFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function delete($fiche)
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
            ->addSelect('poste')->leftJoin('fiche.poste', 'poste')
//            ->addSelect('fichemetier')->leftJoin('fiche.fichesMetiers', 'fichemetier')
//            ->addSelect('metier')->leftJoin('fichemetier.metier', 'metier')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('specificite')->leftJoin('fiche.specificite', 'specificite')
            ->addSelect('externe')->leftJoin('fiche.fichesMetiers', 'externe')
            ;
        return $qb;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostes()
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('poste')->leftJoin('fiche.poste', 'poste')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('specificite')->leftJoin('fiche.specificite', 'specificite')
            ->addSelect('externe')->leftJoin('fiche.fichesMetiers', 'externe')
            ->andWhere('fiche.histoDestruction IS NULL')
            ->orderBy('fiche.id', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FichePoste
     */
    public function getFichePoste($id)
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('statut')->leftJoin('agent.statuts', 'statut')
            ->addSelect('agentgrade')->leftJoin('agent.grades', 'agentgrade')
            ->addSelect('grade')->leftJoin('agentgrade.grade', 'grade')
            ->addSelect('corps')->leftJoin('agentgrade.corps', 'corps')
            ->addSelect('correspondance')->leftJoin('agentgrade.bap', 'correspondance')

            ->addSelect('agentmission')->leftJoin('agent.missionsSpecifiques', 'agentmission')
            ->addSelect('mission')->leftJoin('agentmission.mission', 'mission')
            ->addSelect('m_structure')->leftJoin('agentmission.structure', 'm_structure')

            ->addSelect('poste')->leftJoin('fiche.poste', 'poste')
            ->addSelect('poste_domaine')        ->leftJoin('poste.domaine', 'poste_domaine')
            ->addSelect('poste_structure')      ->leftJoin('poste.structure', 'poste_structure')
            ->addSelect('poste_structure_t')    ->leftJoin('poste_structure.type', 'poste_structure_t')
            ->addSelect('poste_correspondance') ->leftJoin('poste.correspondance', 'poste_correspondance')
            ->addSelect('poste_responsable')    ->leftJoin('poste.rattachementHierarchique', 'poste_responsable')


            ->addSelect('externe')->leftJoin('fiche.fichesMetiers', 'externe')
            ->addSelect('fichemetier')->leftJoin('externe.ficheType', 'fichemetier')
            ->addSelect('metier')->leftJoin('fichemetier.metier', 'metier')
            ->addSelect('domaine')->leftJoin('metier.domaines', 'domaine')
//            ->addSelect('fmApplication')->join('fichemetier.applications', 'fmApplication')
//            ->addSelect('fmCompetence')->join('fichemetier.competences', 'fmCompetence')
//            ->addSelect('fmFormation')->join('fichemetier.formations', 'fmFormation')
            //activite
            ->addSelect('ftActivite')->leftJoin('fichemetier.activites', 'ftActivite')
            ->addSelect('activite')->leftJoin('ftActivite.activite', 'activite')
            ->addSelect('aLibelle')->leftJoin('activite.libelles', 'aLibelle')
            ->addSelect('aApplication')->leftJoin('activite.applications', 'aApplication')
            ->addSelect('aCompetence')->leftJoin('activite.competences', 'aCompetence')
            ->addSelect('aFormation')->leftJoin('activite.formations', 'aFormation')
            ->addSelect('aDescription')->leftJoin('activite.descriptions', 'aDescription')

            ->addSelect('expertise')->leftJoin('fiche.expertises', 'expertise')
            ->addSelect('specificite')->leftJoin('fiche.specificite', 'specificite')
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
    public function getRequestedFichePoste($controller, $paramName = 'fiche-poste', $notNull = false)
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
    public function createFicheTypeExterne($ficheTypeExterne)
    {
        try {
            $this->getEntityManager()->persist($ficheTypeExterne);
            $this->getEntityManager()->flush($ficheTypeExterne);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'ajout d'une fiche metier externe.", $e);
        }
        return $ficheTypeExterne;
    }

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function updateFicheTypeExterne($ficheTypeExterne)
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
    public function deleteFicheTypeExterne($ficheTypeExterne)
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
    public function getFicheTypeExterne($id)
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
    public function getFichesPostesByStructures($structures = [], $sousstructure = false)
    {
        try {
            $today = new DateTime();
            $noEnd = DateTime::createFromFormat('d/m/Y H:i:s', '31/12/1999 00:00:00');
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
            ->andWhere('statut.fin >= :today OR statut.fin IS NULL')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
            ->andWhere('statut.administratif = :true')
            ->setParameter('today', $today)
            //->setParameter('noEnd', $noEnd)
            ->setParameter('true', 'O')
            ->orderBy('agent.nomUsuel, agent.prenom')
        ;

        $qb = $qb
            ->andWhere('statut.structure IN (:structures)')
            ->setParameter('structures', $structures);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param boolean $sousstructure
     * @return FichePoste[]
     */
    public function getFichesPostesSansAgentByStructure($structure, $sousstructure = false)
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
                foreach ($fichemetier->getApplications() as $application) {
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
                        foreach ($activite->getActivite()->getApplications() as $application) {
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
        if ($fiche->getAgent()) {
            foreach ($fiche->getAgent()->getGradesActifs() as $grade) {
                $structure = $grade->getStructure();
                if ($this->getStructureService()->isGestionnaire($structure, $user)) return true;
            }
        }
        return false;
    }

    /** Dictionnaires associés aux fiches de poste ********************************************************************/

    /**
     * @param FichePoste $fiche
     * @param DateTime $date
     * @return array
     */
    public function getActivitesDictionnaires(FichePoste $fiche, DateTime $date) {

        $dictionnaire = [];

        /** Recuperation des fiches metiers */
        foreach ($fiche->getFichesMetiers() as $ficheTypeExterne) {
            $ficheMetier = $ficheTypeExterne->getFicheType();
            $fichesMetiers[] = $ficheMetier;
            $activitesId = explode(';',$ficheTypeExterne->getActivites());
            foreach ($ficheMetier->getActivites() as $metierTypeActivite) {
                $id = $metierTypeActivite->getActivite()->getId();
                $dictionnaire[$id]["object"] = $metierTypeActivite; //TODO voir si pertinent
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
    public function getApplicationsDictionnaires(FichePoste $fiche, DateTime $date)
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
            foreach ($ficheMetier->getApplications() as $application) {
                $dictionnaire[$application->getId()]["object"] = $application;
                $dictionnaire[$application->getId()]["raison"][] = $ficheMetier;
                $dictionnaire[$application->getId()]["conserve"] = true;
            }
        }

        foreach ($activites as $activite) {
            foreach ($activite->getApplications() as $application) {
                $dictionnaire[$application->getId()]["object"] = $application;
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
    public function getFormationsDictionnaires(FichePoste $fiche, DateTime $date)
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

        $retirees = $fiche->getFormationsRetirees();
        foreach ($retirees as $retiree) {
            $dictionnaire[$retiree->getFormation()->getId()]["conserve"] = false;
        }

        return $dictionnaire;
    }

    /**
     * @param FichePoste $fiche
     * @param DateTime $date
     * @return array
     */
    public function getCompetencesDictionnaires(FichePoste $fiche, DateTime $date)
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
            foreach ($ficheMetier->getCompetences() as $competence) {
                $dictionnaire[$competence->getId()]["object"] = $competence;
                $dictionnaire[$competence->getId()]["raison"][] = $ficheMetier;
                $dictionnaire[$competence->getId()]["conserve"] = true;
            }
        }

        foreach ($activites as $activite) {
            foreach ($activite->getCompetences() as $competence) {
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
}