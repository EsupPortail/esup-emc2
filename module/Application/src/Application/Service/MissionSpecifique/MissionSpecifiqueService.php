<?php

namespace Application\Service\MissionSpecifique;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\Structure;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MissionSpecifiqueService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** ENTITY MANAGEMENT *********************************************************************************************/

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function create($affectation)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoCreation($date);
        $affectation->setHistoCreateur($user);
        $affectation->setHistoModification($date);
        $affectation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($affectation);
            $this->getEntityManager()->flush($affectation);
        } catch (ORMException $e) {
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
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoModification($date);
        $affectation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($affectation);
        } catch (ORMException $e) {
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
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoDestruction($date);
        $affectation->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($affectation);
        } catch (ORMException $e) {
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
        $affectation->setHistoDestruction(null);
        $affectation->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($affectation);
        } catch (ORMException $e) {
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
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() {

        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('affectation')
            ->addSelect('agent')->leftJoin('affectation.agent', 'agent')
            ->addSelect('mission')->leftJoin('affectation.mission', 'mission')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
        ;

        return $qb;
    }

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
     * @param Structure $structure
     * @param bool $sousstructure
     * @return AgentMissionSpecifique[]
     */
    public function getMissionsSpecifiquesByStructure(Structure $structure, $sousstructure = false)
    {
        $today = $this->getDateTime();

        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('mission')
            ->addSelect('structure')->join('mission.structure', 'structure')
            ->andWhere('mission.structure = :structure OR structure.parent = :structure')
            ->andWhere('mission.dateFin >= :today OR mission.dateFin IS NULL')
            ->setParameter('structure', $structure)
            ->setParameter('today', $today);
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @param boolean $active
     * @return AgentMissionSpecifique[]
     */
    public function getMissionsSpecifiquesByStructures($structures, $active = true)
    {
        $date = $this->getDateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere('affectation.structure IN (:structures)')
            ->setParameter('structures', $structures)
            ->orderBy('agent.nomUsuel, agent.prenom, structure.libelleLong, mission.libelle', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}