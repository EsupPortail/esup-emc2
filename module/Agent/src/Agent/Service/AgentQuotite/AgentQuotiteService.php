<?php

namespace Agent\Service\AgentQuotite;

use Agent\Entity\Db\AgentQuotite;
use Application\Entity\Db\Agent;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use RuntimeException;

class AgentQuotiteService {
    use ProvidesObjectManager;

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentQuotite::class)->createQueryBuilder('agentquotite')
            ->join('agentquotite.agent', 'agent')->addSelect('agent')
            ->andWhere('agentquotite.deletedOn IS NULL')
        ;
        return $qb;
    }

    /**
     * @param Agent $agent
     * @param bool $actif
     * @return array
     */
    public function getAgentQuotitesbyAgent(Agent $agent, bool $actif = true) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentquotite.agent = :agent')
            ->andWhere('agentquotite.deletedOn IS NULL')
            ->setParameter('agent', $agent)
        ;

        if ($actif === true) $qb = AgentQuotite::decorateWithActif($qb, 'agentquotite');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent $agent
     * @return AgentQuotite|null
     * @attention null est equivalent à une quotité de 100%
     */
    public function getAgentQuotiteCurrent(Agent $agent) : ?AgentQuotite
    {
        $result = $this->getAgentQuotitesbyAgent($agent);
        $nb = count($result);

        if ($nb === 0) return null;
        if ($nb > 1) throw new RuntimeException("Plusieurs AgentQuotite de retournées pour l'agent [".$agent->getId()."]");
        return $result[0];
    }
}