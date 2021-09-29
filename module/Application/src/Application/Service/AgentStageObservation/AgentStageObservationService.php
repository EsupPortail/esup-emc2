<?php

namespace Application\Service\AgentStageObservation;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentStageObservation;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class AgentStageObservationService {
    use GestionEntiteHistorisationTrait;

    /** Gestion des entites ***************************************************************************************/

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function create(AgentStageObservation $stageobs) : AgentStageObservation
    {
        $this->createFromTrait($stageobs);
        return $stageobs;
    }

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function update(AgentStageObservation $stageobs) : AgentStageObservation
    {
        $this->updateFromTrait($stageobs);
        return $stageobs;
    }

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function restore(AgentStageObservation $stageobs)  :AgentStageObservation
    {
        $this->restoreFromTrait($stageobs);
        return $stageobs;
    }

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function historise(AgentStageObservation $stageobs) : AgentStageObservation
    {
        $this->historiserFromTrait($stageobs);
        return $stageobs;
    }

    /**
     * @param AgentStageObservation $stageobs
     * @return AgentStageObservation
     */
    public function delete(AgentStageObservation $stageobs) : AgentStageObservation
    {
        $this->deleteFromTrait($stageobs);
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