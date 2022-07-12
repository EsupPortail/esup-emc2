<?php

namespace Application\Service\AgentStageObservation;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentStageObservation;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class AgentStageObservationService {
    use EntityManagerAwareTrait;

    /** Gestion des entites ***************************************************************************************/

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function create(AgentStageObservation $stageobs) : AgentStageObservation
    {
        try {
            $this->getEntityManager()->persist($stageobs);
            $this->getEntityManager()->flush($stageobs);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $stageobs;
    }

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function update(AgentStageObservation $stageobs) : AgentStageObservation
    {
        try {
            $this->getEntityManager()->flush($stageobs);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $stageobs;
    }

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function historise(AgentStageObservation $stageobs) : AgentStageObservation
    {
        try {
            $stageobs->historiser();
            $this->getEntityManager()->flush($stageobs);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $stageobs;
    }

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function restore(AgentStageObservation $stageobs)  :AgentStageObservation
    {
        try {
            $stageobs->dehistoriser();
            $this->getEntityManager()->flush($stageobs);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $stageobs;
    }

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function delete(AgentStageObservation $stageobs) : AgentStageObservation
    {
        try {
            $this->getEntityManager()->remove($stageobs);
            $this->getEntityManager()->flush($stageobs);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $stageobs;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {

        $qb = $this->getEntityManager()->getRepository(AgentStageObservation::class)->createQueryBuilder('stageobs')
            ->join('stageobs.agent', 'agent')->addSelect('agent')
            ->leftjoin('stageobs.structure', 'structure')->addSelect('structure')
            ->leftjoin('stageobs.metier', 'metier')->addSelect('metier')
            ->leftjoin('stageobs.etat', 'etat')->addSelect('etat');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return AgentStageObservation|null
     */
    public function getAgentStageObservation(?int $id) : ?AgentStageObservation
    {
        $qb = $this->createQueryBuilder()->andWhere('stageobs.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs AgentStageObservation partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return AgentStageObservation|null
     */
    public function getRequestedAgentStageObservation(AbstractActionController $controller, string $param='stageobs') : ?AgentStageObservation
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getAgentStageObservation($id);
        return $result;
    }

    /**
     * @param Agent $agent
     * @return AgentStageObservation[]
     */
    public function getAgentStageObservationsByAgent(Agent $agent) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('stageobs.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('stageobs.dateDebut','DESC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}