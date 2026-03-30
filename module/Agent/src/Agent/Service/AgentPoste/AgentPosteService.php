<?php

namespace Agent\Service\AgentPoste;

use Agent\Entity\Db\AgentPoste;
use Application\Entity\Db\Agent;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\CodeFonction;

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

    /** @return AgentPoste[] */
    public function getAgentsPostesByCodeFonction(?CodeFonction $codeFonction): array
    {
        if ($codeFonction === null) return [];
        $code = $codeFonction->computeCode();
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.codeFonction = :code')->setParameter('code', $code)
            ->andWhere('poste.deletedOn IS NULL')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}