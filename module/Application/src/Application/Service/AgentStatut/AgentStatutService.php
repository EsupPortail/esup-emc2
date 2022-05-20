<?php

namespace Application\Service\AgentStatut;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentStatut;
use Doctrine\ORM\QueryBuilder;
use Structure\Entity\Db\Structure;
use UnicaenApp\Service\EntityManagerAwareTrait;

class AgentStatutService {
    use EntityManagerAwareTrait;

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentStatut::class)->createQueryBuilder('agentstatut')
            ->join('agentstatut.agent', 'agent')->addSelect('agent')
            ->join('agentstatut.structure', 'structure')->addSelect('structure')
            ->andWhere('agentstatut.deleted_on IS NULL')
        ;
        return $qb;
    }

    /**
     * @param Agent $agent
     * @param bool $actif
     * @return array
     */
    public function getAgentStatutsByAgent(Agent $agent, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentstatut.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('agentstatut.dateDebut', 'DESC')
        ;

        if ($actif === true) $qb = AgentStatut::decorateWithActif($qb, 'agentstatut');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param bool $actif
     * @return array
     */
    public function getAgentStatutsByStructure(Structure $structure, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentstatut.structure = :structure')
            ->setParameter('structure', $structure)
        ;

        if ($actif === true) $qb = AgentStatut::decorateWithActif($qb, 'agentstatut');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}