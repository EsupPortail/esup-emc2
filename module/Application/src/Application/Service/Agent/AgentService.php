<?php

namespace Application\Service\Agent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentApplication;
use Application\Entity\Db\AgentCompetence;
use Application\Entity\Db\AgentFormation;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\Structure;
use Application\Service\GestionEntiteHistorisationTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class AgentService {
//    use DateTimeAwareTrait;
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;
    use StructureServiceAwareTrait;


    /** GESTION DES ENTITÉS *******************************************************************************************/

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function update($agent)
    {
        try {
            $this->getEntityManager()->flush($agent);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la mise à jour de l'agent", $e);
        }
        return $agent;
    }

    /** REQUETES ******************************************************************************************************/

    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
//            ->addSelect('statut')->leftJoin('agent.statuts', 'statut')
//            ->addSelect('grade')->leftJoin('agent.grades', 'grade')

//            ->addSelect('fichePoste')->leftJoin('agent.fiches','fichePoste')
//            ->addSelect('fpPoste')->leftJoin('fichePoste.poste', 'fpPoste')
//            ->addSelect('structure')->leftJoin('fichePoste.structure', 'structure')
//
            ->addSelect('missionSpecifique')->leftJoin('agent.missionsSpecifiques', 'missionSpecifique')
            ->addSelect('structureM')->leftJoin('missionSpecifique.structure', 'structureM')
            ->addSelect('mission')->leftJoin('missionSpecifique.mission', 'mission')

            ->addSelect('entretien')->leftJoin('agent.entretiens', 'entretien')
            ->addSelect('entretienValidationAgent')->leftJoin('entretien.validationAgent', 'entretienValidationAgent')
//            ->addSelect('evaModificateur')->leftJoin('entretienValidationAgent.histoModificateur', 'evaModificateur')
            ->addSelect('entretienValidationResponsable')->leftJoin('entretien.validationResponsable', 'entretienValidationResponsable')
//            ->addSelect('evrModificateur')->leftJoin('entretienValidationResponsable.histoModificateur', 'evrModificateur')



            ->addSelect('utilisateur')->leftJoin('agent.utilisateur', 'utilisateur')
        ;
        return $qb;
    }


    /**
     * @param string $order
     * @return Agent[]
     */
    public function getAgents($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
        ;

        if ($order !== null) {
            $qb = $qb->orderBy('agent.' . $order);
        } else {
            $qb = $qb->orderBy('agent.nomUsuel, agent.prenom');
        }

        $result =  $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $term
     * @param Structure[] $structures
     * @return Agent[]
     */
    public function getAgentsByTerm($term, $structures = null)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like :search")
            ->setParameter('search', '%'.strtolower($term).'%')
        ;

        if ($structures !== null) {
            $date = $this->getDateTime();
            $qb = $qb
                ->addSelect('grade')->join('agent.grades', 'grade')
                ->andWhere('grade.dateDebut <= :date')
                ->andWhere('grade.dateFin IS NULL OR grade.dateFin >= :date')
                ->setParameter('date', $date)
                ->addSelect('structure')->join('grade.structure', 'structure')
                ->andWhere('structure IN (:structures)')
                ->setParameter('structures', $structures)
            ;
        }
        $result =  $qb->getQuery()->getResult();
        return $result;
    }


    /**
     * @param integer $id
     * @return Agent
     */
    public function getAgent($id)
    {
        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs agents partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Agent
     */
    public function getRequestedAgent($controller, $paramName = 'agent')
    {
        $id = $controller->params()->fromRoute($paramName);
        $agent = $this->getAgent($id);
        return $agent;
    }

    /**
     * @param User $user
     * @return Agent
     */
    public function getAgentByUser(User $user)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.utilisateur = :user')
            ->setParameter('user', $user)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent liés au même User [".$user->getId()."]", $e);
        }
        return $result;
    }

    /**
     * @param int $supannId
     * @return Agent
     */
    public function getAgentBySupannId($supannId)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.sourceName = :harp')
            ->andWhere('agent.sourceId = :supannId')
            ->setParameter('harp', 'HARP')
            ->setParameter('supannId', $supannId);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs agents partagent le même identifiant [".$supannId."]");
        }
        return $result;
    }

    /**
     * @param Structure $structure
     * @param boolean $sousstructure
     * @return Agent[]
     */
    public function getAgentsSansFichePosteByStructure($structure = null, $sousstructure = false)
    {
        $today = $this->getDateTime();

        /** !!TODO!! faire le lien entre agent et fiche de poste */
        $qb1 = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->addSelect('grade')->join('agent.grades', 'grade')
            ->addSelect('structure')->join('grade.structure', 'structure')
            ->addSelect('fiche')->leftJoin('agent.fiches', 'fiche')
            ->andWhere('statut.fin >= :today OR statut.fin IS NULL')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
            ->andWhere('statut.administratif = :true')
            //->andWhere('fiche.id IS NULL')
            ->setParameter('today', $today)
            ->setParameter('true', 'O')
            ->orderBy('agent.nomUsuel, agent.prenom')
        ;
        if ($structure !== null && $sousstructure === true) {
            $qb1 = $qb1->andWhere('grade.structure = :structure OR structure.parent = :structure')
                     ->setParameter('structure', $structure);
        }
        if ($structure !== null && $sousstructure === false) {
            $qb1 = $qb1->andWhere('statut.structure = :structure' )
                ->setParameter('structure', $structure);
        }
        $result1 = $qb1->getQuery()->getResult();


        //TODO ! faire la jointure ...
        $result = [];
        /** @var Agent $agent */
        foreach ($result1 as $agent) {
            if (empty($agent->getFiches())) $result[] = $agent;
        }

        return $result;

    }

    /**
     * @param Structure[] $structures
     * @return Agent[]
     */
    public function getAgentsByStructures($structures)
    {
        $today = $this->getDateTime();

        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->addSelect('grade')->join('agent.grades', 'grade')
            ->addSelect('structure')->join('grade.structure', 'structure')
            ->addSelect('ggrade')->join('grade.grade', 'ggrade')
            ->andWhere('statut.fin >= :today OR statut.fin IS NULL')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
            ->andWhere('statut.administratif = :true')
            ->setParameter('today', $today)
            ->setParameter('true', 'O')
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')

            ->addSelect('ficheposte')->leftJoin('agent.fiches', 'ficheposte')
            ->addSelect('poste')->leftJoin('ficheposte.poste', 'poste')
        ;

        if ($structures !== null) {
            $qb = $qb->andWhere('grade.structure IN (:structures)')
                ->setParameter('structures', $structures);
        }

        $result = $qb->getQuery()->getResult();

        return $result;

    }

    /** AgentApplication **********************************************************************************************/

    /**
     * @param integer $id
     * @return AgentApplication
     */
    public function getAgentApplication($id)
    {
        $qb = $this->getEntityManager()->getRepository(AgentApplication::class)->createQueryBuilder('agentapplication')
            ->andWhere('agentapplication.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException("Plusieurs AgentApplication partagent le même identifiant [". $id ."].", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return AgentApplication
     */
    public function getRequestedAgenApplication($controller, $paramName = 'agent-application')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getAgentApplication($id);
        return $result;
    }

    /**
     * @param AgentApplication $agentApplication
     * @return AgentApplication
     */
    public function createAgentApplication(AgentApplication $agentApplication)
    {
        $this->createFromTrait($agentApplication);
        return $agentApplication;
    }

    /**
     * @param AgentApplication $agentApplication
     * @return AgentApplication
     */
    public function updateAgentApplication(AgentApplication $agentApplication)
    {
        $this->updateFromTrait($agentApplication);
        return $agentApplication;
    }

    /**
     * @param AgentApplication $agentApplication
     * @return mixed
     */
    public function historiserAgentApplication(AgentApplication $agentApplication)
    {
        $this->historiserFromTrait($agentApplication);
        return $agentApplication;
    }

    /**
     * @param AgentApplication $agentApplication
     * @return AgentApplication
     */
    public function restoreAgentApplication(AgentApplication $agentApplication)
    {
        $this->restoreFromTrait($agentApplication);
        return $agentApplication;
    }

    /**
     * @param AgentApplication $agentApplication
     * @return AgentApplication
     */
    public function deleteAgentApplication(AgentApplication $agentApplication)
    {
        $this->deleteFromTrait($agentApplication);
        return $agentApplication;
    }

    /** AgentCompetence ***********************************************************************************************/

    /**
     * @param integer $id
     * @return AgentCompetence
     */
    public function getAgentCompetence($id)
    {
        $qb = $this->getEntityManager()->getRepository(AgentCompetence::class)->createQueryBuilder('competence')
            ->andWhere('competence.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException("Plusieurs AgentCompetence partagent le même identifiant [". $id ."].", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return AgentCompetence
     */
    public function getRequestedAgentCompetence($controller, $paramName = 'agent-competence')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getAgentCompetence($id);
        return $result;
    }

    /**
     * @param AgentCompetence $competence
     * @return AgentCompetence
     */
    public function createAgentCompetence($competence)
    {
        $this->createFromTrait($competence);
        return $competence;
    }

    /**
     * @param AgentCompetence $competence
     * @return AgentCompetence
     */
    public function updateAgentCompetence($competence)
    {
        $this->updateFromTrait($competence);
        return $competence;
    }

    /**
     * @param AgentCompetence $competence
     * @return AgentCompetence
     */
    public function historiserAgentCompetence(AgentCompetence $competence)
    {
        $this->historiserFromTrait($competence);
        return $competence;
    }

    /**
     * @param AgentCompetence $competence
     * @return AgentCompetence
     */
    public function restoreAgentCompetence(AgentCompetence $competence)
    {
        $this->restoreFromTrait($competence);
        return $competence;
    }

    /**
     * @param AgentCompetence $competence
     * @return AgentCompetence
     */
    public function deleteAgentCompetence(AgentCompetence $competence)
    {
        $this->deleteFromTrait($competence);
        return $competence;
    }

    /** AgentFormation ************************************************************************************************/

    /**
     * @param integer $id
     * @return AgentFormation
     */
    public function getAgentFormation($id)
    {
        $qb = $this->getEntityManager()->getRepository(AgentFormation::class)->createQueryBuilder('formation')
            ->andWhere('formation.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException("Plusieurs AgentFormation partagent le même identifiant [". $id ."].", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return AgentFormation
     */
    public function getRequestedAgentFormation($controller, $paramName = 'agent-formation')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getAgentFormation($id);
        return $result;
    }

    /**
     * @param AgentFormation $agentFormation
     * @return AgentFormation
     */
    public function createAgentFormation(AgentFormation $agentFormation)
    {
        $this->createFromTrait($agentFormation);
        return $agentFormation;
    }

    /**
     * @param AgentFormation $agentFormation
     * @return AgentFormation
     */
    public function updateAgentFormation(AgentFormation $agentFormation)
    {
        $this->updateFromTrait($agentFormation);
        return $agentFormation;
    }

    /**
     * @param AgentFormation $agentFormation
     * @return AgentFormation
     */
    public function historiserAgentFormation(AgentFormation $agentFormation)
    {
       $this->historiserFromTrait($agentFormation);
        return $agentFormation;
    }

    /**
     * @param AgentFormation $agentFormation
     * @return AgentFormation
     */
    public function restoreAgentFormation(AgentFormation $agentFormation)
    {
       $this->restoreFromTrait($agentFormation);
        return $agentFormation;
    }

    /**
     * @param AgentFormation $agentFormation
     * @return AgentFormation
     */
    public function deleteAgentFormation(AgentFormation $agentFormation)
    {
        $this->deleteFromTrait($agentFormation);
        return $agentFormation;
    }

    /** MISSION SPECIFIQUE ********************************************************************************************/

    /**
     * @param integer $id
     * @return AgentMissionSpecifique
     */
    public function getAgentMissionSpecifique($id)
    {
        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('mission')
            ->andWhere('mission.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException("Plusieurs AgentMissionSpecifique partagent le même identifiant [". $id ."].", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return AgentMissionSpecifique
     */
    public function getRequestedAgentMissionSpecifique($controller, $paramName = 'agent-mission-specifique')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getAgentMissionSpecifique($id);
        return $result;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function createAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->createFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function updateAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->updateFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function historiserAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->historiserFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function restoreAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->restoreFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function deleteAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $this->deleteFromTrait($agentMissionSpecifique);
        return $agentMissionSpecifique;
    }
}