<?php

namespace Application\Service\AgentPoste;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentPoste;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;

class AgentPosteService
{
    use ProvidesObjectManager;

    /** QUERYING *********************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentPoste::class)->createQueryBuilder('poste')
            ->join('poste.agent', 'agent')->addSelect('agent');
        return $qb;
    }

    /** @return AgentPoste[] */
    public function getPostesAsAgent(Agent $agent): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.agent = :agent')->setParameter('agent', $agent)
            ->andWhere('poste.deletedOn IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}