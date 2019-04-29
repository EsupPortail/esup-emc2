<?php

namespace Application\Service\Agent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\MissionComplementaire;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Entity\Ldap\People;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class AgentService {
    use EntityManagerAwareTrait;

    /**
     * @param string $order
     * @return Agent[]
     */
    public function getAgents($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent');

        if ($order !== null) {
            $qb = $qb->orderBy('agent.' . $order);
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
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
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
    public function getRequestedAgent($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $agent = $this->getAgent($id);
        return $agent;
    }

    /**
     * @param User $user
     * @return Agent
     */
    public function getAgentByUser($user)
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
     * @param People $people
     * @param User user
     * @return Agent
     */
    public function createFromLDAP($people, $user)
    {
        $agent = new Agent();
        $agent->setUtilisateur($user);
        $agent->setNom($people->getNomUsuel());
        $agent->setPrenom($people->getGivenName());

        $this->create($agent);
    }

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function create($agent)
    {
        $this->getEntityManager()->persist($agent);
        try {
            $this->getEntityManager()->flush($agent);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la création de l'agent", $e);
        }
        return $agent;
    }

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function update($agent)
    {
        try {
            $this->getEntityManager()->flush($agent);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la mise à jour de l'agent", $e);
        }
        return $agent;
    }

    /**
     * @param Agent $agent
     */
    public function delete($agent)
    {
        $this->getEntityManager()->remove($agent);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la suppression de l'agent", $e);
        }
    }

    /** MISSION COMP **************************************************************************************************/

    public function getMissionComplementaire($id)
    {
        $qb = $this->getEntityManager()->getRepository(MissionComplementaire::class)->createQueryBuilder('mission')
            ->andWhere('mission.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs missions complémentaires partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param MissionComplementaire $mission
     * @return MissionComplementaire
     */
    public function createMissionComplementaire($mission)
    {
        $this->getEntityManager()->persist($mission);
        try {
            $this->getEntityManager()->flush($mission);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la création de la mission complémentaire", $e);
        }
        return $mission;
    }

    /**
     * @param MissionComplementaire $mission
     * @return MissionComplementaire
     */
    public function updateMissionComplementaire($mission)
    {
        try {
            $this->getEntityManager()->flush($mission);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la mise à jour de la mission complémentaire", $e);
        }
        return $mission;
    }

    /**
     * @param MissionComplementaire $mission
     */
    public function deleteMissionComplementaire($mission)
    {
        $this->getEntityManager()->remove($mission);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la suppression de la mission complémentaire", $e);
        }
    }
}