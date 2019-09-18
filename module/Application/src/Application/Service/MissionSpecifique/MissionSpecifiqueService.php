<?php

namespace Application\Service\MissionSpecifique;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\Structure;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MissionSpecifiqueService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param Agent $agent
     * @param MissionSpecifique $mission
     * @param Structure $structure
     * @return AgentMissionSpecifique[]
     */
    public function getAffectations($agent, $mission, $structure)
    {
        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('affectation')
            ->addSelect('agent')->leftJoin('affectation.agent', 'agent')
            ->addSelect('mission')->leftJoin('affectation.mission', 'mission')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
        ;

        if ($agent !== null) {
            $qb = $qb->andWhere('agent.id = :agentId')
                ->setParameter('agentId', $agent->getId())
            ;
        }
        if ($mission !== null) {
            $qb = $qb->andWhere('mission.id = :missionId')
                ->setParameter('missionId', $mission->getId())
            ;
        }
        if ($structure !== null) {
            $qb = $qb->andWhere('structure.id = :structureId')
                ->setParameter('structureId', $structure->getId())
            ;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return AgentMissionSpecifique
     */
    public function getAffectation($id)
    {
        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('affectation')
            ->addSelect('agent')->leftJoin('affectation.agent', 'agent')
            ->addSelect('mission')->leftJoin('affectation.mission', 'mission')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->andWhere('affectation.id = :id')
            ->setParameter('id', $id)
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs AgentMissionSpecifique partagent le même id [".$id."]", $e);
        }
        return $result;

    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return AgentMissionSpecifique
     */
    public function getRequestedAffectation($controller, $paramName='affectation')
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getAffectation($id);
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function create($affectation)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoCreation($date);
        $affectation->setHistoCreateur($user);
        $affectation->setHistoModification($date);
        $affectation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($affectation);
            $this->getEntityManager()->flush($affectation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function update($affectation)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoModification($date);
        $affectation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($affectation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function historise($affectation)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoDestruction($date);
        $affectation->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($affectation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function restore($affectation)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoDestruction(null);
        $affectation->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($affectation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function delete($affectation)
    {
        try {
            $this->getEntityManager()->remove($affectation);
            $this->getEntityManager()->flush($affectation);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    public function getMissionsSpecifiquesByStructure(Structure $structure)
    {
        try {
            $today = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la création des dates");
        }

        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('mission')
            ->andWhere('mission.structure = :structure')
            ->andWhere('mission.dateFin >= :today OR mission.dateFin IS NULL')
            ->setParameter('structure', $structure)
            ->setParameter('today', $today);
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}