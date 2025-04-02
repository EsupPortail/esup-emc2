<?php

namespace Agent\Service\AgentRef;

use Agent\Entity\Db\AgentRef;
use Application\Controller\AgentController;
use Application\Entity\Db\Agent;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use RuntimeException;

class AgentRefService
{
    use ProvidesObjectManager;

    /** Gestion de l'entité : importer seulement */

    /** Requetage */

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentRef::class)->createQueryBuilder('agentref')
            ->join('agentref.agent', 'agent')->addSelect('agent')
            ->andWhere('agentref.deletedOn IS NULL')
            ->andWhere('agent.deletedOn IS NULL')
        ;
        return $qb;
    }

    public function getAgentsRef(?string $id): ?AgentRef
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentref.id = :id')->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".AgentRef::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedAgentRef(AgentController $controller, string $param='agent-ref'): ?AgentRef
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getAgentsRef($id);
    }

    /** @return AgentRef[] */
    public function getAgentsRefs(): array
    {
        $qb = $this->createQueryBuilder();
        return $qb->getQuery()->getResult();
    }

    public function getAgentByRef(string $source, string $idSource): ?Agent
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentref.source = :source')->setParameter('source', $source)
            ->andWhere('agentref.idSource = :idSource')->setParameter('idSource', $idSource)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".AgentRef::class."] partagent la même ref [".$source."|".$idSource."]",0,$e);
        }
        return $result?->getAgent();
    }

    /** advanced */

    /** @return string[] */
    public function getSources(): array
    {
        $params = [];
        $sql = <<<EOS
select source from agent_ref group by source
EOS;

        try {
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, $params);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("[DRV] Un problème est survenue lors de la récupération des sources", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("[DBA] Un problème est survenue lors de la récupération des sources", 0, $e);
        }
        return $tmp;
    }
}