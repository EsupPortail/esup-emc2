<?php

namespace Agent\Service\AgentStatut;

use Agent\Entity\Db\AgentStatut;
use Agent\Entity\Db\Agent;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Structure\Entity\Db\Structure;

class AgentStatutService
{
    use ProvidesObjectManager;

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentStatut::class)->createQueryBuilder('agentstatut')
            ->join('agentstatut.agent', 'agent')->addSelect('agent')
            ->join('agentstatut.structure', 'structure')->addSelect('structure')
            ->andWhere('agentstatut.deletedOn IS NULL');
        return $qb;
    }

    /**
     * @param Agent $agent
     * @param bool $actif
     * @return array
     */
    public function getAgentStatutsByAgent(Agent $agent, bool $actif = true): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentstatut.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('agentstatut.dateDebut', 'DESC');

        if ($actif === true) $qb = AgentStatut::decorateWithActif($qb, 'agentstatut');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param ?DateTime $date
     * @return array (AgentId => AgentStatut[])
     */
    public function getAgentStatutsByAgents(array $agents, ?DateTime $date = null): array
    {
        if ($date === null) $date = new DateTime();

        $qb = $this->createQueryBuilder()
            ->andWhere('agentstatut.agent in (:agents)')->setParameter('agents', $agents)
            ->orderBy('agentstatut.dateDebut', 'DESC')
            ->andWhere('agentstatut.dateDebut IS NULL OR agentstatut.dateDebut <= :date')
            ->andWhere('agentstatut.dateFin IS NULL OR agentstatut.dateFin >= :date')
            ->setParameter('date', $date)
        ;

        $result = $qb->getQuery()->getResult();

        $array = [];
        foreach ($result as $statut) {
            $agent = $statut->getAgent();
            $array[$agent->getId()][] = $statut;
        }

        return $array;
    }

    /**
     * @param Structure $structure
     * @param bool $actif
     * @return array
     */
    public function getAgentStatutsByStructure(Structure $structure, bool $actif = true): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentstatut.structure = :structure')
            ->setParameter('structure', $structure);

        if ($actif === true) $qb = AgentStatut::decorateWithActif($qb, 'agentstatut');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}