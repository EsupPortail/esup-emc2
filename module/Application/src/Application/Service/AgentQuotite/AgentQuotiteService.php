<?php

namespace Application\Service\AgentQuotite;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentQuotite;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class AgentQuotiteService {
    use EntityManagerAwareTrait;

    /** GESTION ENTITE ************************************************************************************************/
    // Complétement importées et jamais modifiées

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentQuotite::class)->createQueryBuilder('agentquotite')
            ->join('agentquotite.agent', 'agent')->addSelect('agent')
            ->andWhere('agentquotite.deleted_on IS NULL')
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
            ->andWhere('agentquotite.delete_on IS NULL')
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