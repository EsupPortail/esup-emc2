<?php

namespace Application\Service\Agent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentApplication;
use Application\Entity\Db\AgentCompetence;
use Application\Entity\Db\AgentFormation;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\Application;
use Application\Entity\Db\Structure;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class AgentService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;
    use DateTimeAwareTrait;

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
                ->andWhere('structure IN (:structures)', )
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
     * @param bool $active
     * @return array
     */
    public function getAgentsAsOption($active = true)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->orderBy('agent.nomUsuel, agent.prenom');

        if ($active === true) {
            //TODO
        }

        /** @var Agent[] $result */
        $result = $qb->getQuery()->getResult();

        $agents = [];
        foreach ($result as $item) {
            $agents[$item->getId()] = $item->getDenomination();
        }
        return $agents;
    }

    /**
     * @param Structure $structure
     * @param boolean $sousstructure
     * @return Agent[]
     */
    public function getAgentsSansFichePosteByStructure($structure = null, $sousstructure = false)
    {
        try {
            $today = new DateTime();
            $noEnd = DateTime::createFromFormat('d/m/Y H:i:s', '31/12/1999 00:00:00');
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la création des dates");
        }

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
     * @param Structure $structure
     * @param boolean $sousstructure
     * @return Agent[]
     */
    public function getAgentsByStructure(Structure $structure, $sousstructure = false)
    {
        try {
            $today = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la création des dates");
        }

        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->addSelect('grade')->join('agent.grades', 'grade')
            ->addSelect('structure')->join('grade.structure', 'structure')
            ->andWhere('statut.fin >= :today OR statut.fin IS NULL')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
            ->andWhere('statut.administratif = :true')
            ->setParameter('today', $today)
            ->setParameter('true', 'O')

        ;

        if ($structure !== null AND $sousstructure === false) {
            $qb = $qb->andWhere('grade.structure = :structure')
                ->setParameter('structure', $structure);
        }
        if ($structure !== null AND $sousstructure === true) {
            $qb = $qb->andWhere('grade.structure = :structure OR structure.parent = :structure')
                ->setParameter('structure', $structure);
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
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $agentApplication->setHistoCreation($date);
        $agentApplication->setHistoCreateur($user);
        $agentApplication->setHistoModification($date);
        $agentApplication->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($agentApplication);
            $this->getEntityManager()->flush($agentApplication);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentApplication;
    }

    /**
     * @param AgentApplication $agentApplication
     * @return AgentApplication
     */
    public function updateAgentApplication(AgentApplication $agentApplication)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $agentApplication->setHistoModification($date);
        $agentApplication->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($agentApplication);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentApplication;
    }

    /**
     * @param AgentApplication $agentApplication
     * @return mixed
     */
    public function historiserAgentApplication(AgentApplication $agentApplication)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $agentApplication->setHistoDestruction($date);
        $agentApplication->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($agentApplication);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentApplication;
    }

    /**
     * @param AgentApplication $agentApplication
     * @return AgentApplication
     */
    public function restoreAgentApplication(AgentApplication $agentApplication)
    {
        $agentApplication->setHistoDestruction(null);
        $agentApplication->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($agentApplication);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentApplication;
    }

    /**
     * @param AgentApplication $agentApplication
     * @return AgentApplication
     */
    public function deleteAgentApplication(AgentApplication $agentApplication)
    {

        try {
            $this->getEntityManager()->remove($agentApplication);
            $this->getEntityManager()->flush($agentApplication);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

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
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $competence->setHistoCreation($date);
        $competence->setHistoCreateur($user);
        $competence->setHistoModification($date);
        $competence->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($competence);
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $competence;
    }

    /**
     * @param AgentCompetence $competence
     * @return AgentCompetence
     */
    public function updateAgentCompetence($competence)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $competence->setHistoModification($date);
        $competence->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $competence;
    }

    /**
     * @param AgentCompetence $competence
     * @return AgentCompetence
     */
    public function historiserAgentCompetence(AgentCompetence $competence)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $competence->setHistoDestruction($date);
        $competence->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $competence;
    }

    /**
     * @param AgentCompetence $competence
     * @return AgentCompetence
     */
    public function restoreAgentCompetence(AgentCompetence $competence)
    {
        $competence->setHistoDestruction(null);
        $competence->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $competence;
    }

    /**
     * @param AgentCompetence $competence
     * @return AgentCompetence
     */
    public function deleteAgentCompetence(AgentCompetence $competence)
    {
        try {
            $this->getEntityManager()->remove($competence);
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

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
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $agentFormation->setHistoCreation($date);
        $agentFormation->setHistoCreateur($user);
        $agentFormation->setHistoModification($date);
        $agentFormation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($agentFormation);
            $this->getEntityManager()->flush($agentFormation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentFormation;
    }

    /**
     * @param AgentFormation $agentFormation
     * @return AgentFormation
     */
    public function updateAgentFormation(AgentFormation $agentFormation)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $agentFormation->setHistoModification($date);
        $agentFormation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($agentFormation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentFormation;
    }

    /**
     * @param AgentFormation $agentFormation
     * @return AgentFormation
     */
    public function historiserAgentFormation(AgentFormation $agentFormation)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $agentFormation->setHistoDestruction($date);
        $agentFormation->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($agentFormation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentFormation;
    }

    /**
     * @param AgentFormation $agentFormation
     * @return AgentFormation
     */
    public function restoreAgentFormation(AgentFormation $agentFormation)
    {
        $agentFormation->setHistoDestruction(null);
        $agentFormation->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($agentFormation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentFormation;
    }

    /**
     * @param AgentFormation $agentFormation
     * @return AgentFormation
     */
    public function deleteAgentFormation(AgentFormation $agentFormation)
    {
        try {
            $this->getEntityManager()->remove($agentFormation);
            $this->getEntityManager()->flush($agentFormation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

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
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $agentMissionSpecifique->setHistoCreation($date);
        $agentMissionSpecifique->setHistoCreateur($user);
        $agentMissionSpecifique->setHistoModification($date);
        $agentMissionSpecifique->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($agentMissionSpecifique);
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function updateAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $agentMissionSpecifique->setHistoModification($date);
        $agentMissionSpecifique->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function historiserAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $agentMissionSpecifique->setHistoDestruction($date);
        $agentMissionSpecifique->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function restoreAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        $agentMissionSpecifique->setHistoDestruction(null);
        $agentMissionSpecifique->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentMissionSpecifique;
    }

    /**
     * @param AgentMissionSpecifique $agentMissionSpecifique
     * @return AgentMissionSpecifique
     */
    public function deleteAgentMissionSpecifique(AgentMissionSpecifique $agentMissionSpecifique)
    {
        try {
            $this->getEntityManager()->remove($agentMissionSpecifique);
            $this->getEntityManager()->flush($agentMissionSpecifique);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }

        return $agentMissionSpecifique;
    }
}