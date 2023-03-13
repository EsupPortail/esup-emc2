<?php

namespace Application\Service\AgentPoste;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentPoste;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Service\EntityManagerAwareTrait;

class AgentPosteService {
    use EntityManagerAwareTrait;

    /** QUERYING *********************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentPoste::class)->createQueryBuilder('poste')
            ->join('poste.agent', 'agent')->addSelect('agent');
        return $qb;
    }

    /** @return AgentPoste[] */
    public function getPostesAsAgent(Agent $agent) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.agent = :agent')->setParameter('agent', $agent)
            ->andWhere('poste.deleted_on IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}