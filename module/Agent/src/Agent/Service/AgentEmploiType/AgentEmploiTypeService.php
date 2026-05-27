<?php

namespace Agent\Service\AgentEmploiType;

use Agent\Entity\Db\Agent;
use Agent\Entity\Db\AgentEmploiType;
use Carriere\Entity\Db\EmploiType;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class AgentEmploiTypeService {

    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************/

    public function create(AgentEmploiType $agentEmploiType): void
    {
        $this->getObjectManager()->persist($agentEmploiType);
        $this->getObjectManager()->flush($agentEmploiType);
    }

    public function update(AgentEmploiType $agentEmploiType): void
    {
        $this->getObjectManager()->flush($agentEmploiType);
    }

    public function delete(AgentEmploiType $agentEmploiType): void
    {
        $this->getObjectManager()->remove($agentEmploiType);
        $this->getObjectManager()->flush($agentEmploiType);
    }

    /** QUERYING *******************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentEmploiType::class)->createQueryBuilder('agentEmploiType')
            ->join('agentEmploiType.agent', 'agent')->addSelect('agent')
            ->join('agentEmploiType.emploiType', 'emploiType')->addSelect('emploiType')
            ->andWhere('agentEmploiType.deletedOn IS NULL')
        ;
        return $qb;
    }

    public function getAgentEmploiType(?int $id): ?AgentEmploiType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentEmploiType.id = :id')->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".AgentEmploiType::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedAgentEmploiType(AbstractActionController $controller, string $param='agent-emploi-type'): ?AgentEmploiType
    {
        $id = $controller->params()->fromRoute($param);
        $agentEmploiType = $this->getAgentEmploiType($id);
        return $agentEmploiType;
    }

    /** @return AgentEmploiType[] */
    public function getAgentEmploiTypes(bool $enCours = false): array
    {
        $qb = $this->createQueryBuilder();
        $result = $qb->getQuery()->getResult();

        if ($enCours) {
            $now = new DateTime();
            $qb = $qb->andWhere('coalesce(agentEmploiType.dateDebut,:now) <= :now')
                ->andWhere('coalesce(agentEmploiType.dateFin,:now) >= :now')
                ->setParameter('now', $now);
        }

        return $result;
    }

    /** @return AgentEmploiType[] */
    public function getAgentEmploiTypesByAgent(Agent $agent, bool $enCours = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentEmploiType.agent = :agent')->setParameter('agent', $agent)
        ;
        if ($enCours) {
            $now = new DateTime();
            $qb = $qb->andWhere('coalesce(agentEmploiType.dateDebut,:now) <= :now')
                     ->andWhere('coalesce(agentEmploiType.dateFin,:now) >= :now')
                ->setParameter('now', $now);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentEmploiType[] */
    public function getAgentEmploiTypesByEmploiType(EmploiType $emploiType, bool $enCours = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentEmploiType.emploiType = :emploiType')->setParameter('emploiType', $emploiType)
        ;
        if ($enCours) {
            $now = new DateTime();
            $qb = $qb->andWhere('coalesce(agentEmploiType.dateDebut,:now) <= :now')
                ->andWhere('coalesce(agentEmploiType.dateFin,:now) >= :now')
                ->setParameter('now', $now);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE **********************************************************************************/
}