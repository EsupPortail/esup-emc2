<?php

namespace Agent\Service\AgentEmploiType;

use Agent\Entity\Db\Agent;
use Agent\Entity\Db\AgentEmploiType;
use Agent\Entity\Db\AgentGrade;
use Application\Service\SqlHelper\SqlHelperServiceAwareTrait;
use Carriere\Entity\Db\EmploiType;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class AgentEmploiTypeService
{

    use ProvidesObjectManager;
    use SqlHelperServiceAwareTrait;

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
            ->andWhere('agentEmploiType.deletedOn IS NULL');
        return $qb;
    }

    public function getAgentEmploiType(?int $id): ?AgentEmploiType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentEmploiType.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . AgentEmploiType::class . "] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedAgentEmploiType(AbstractActionController $controller, string $param = 'agent-emploi-type'): ?AgentEmploiType
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
            ->andWhere('agentEmploiType.agent = :agent')->setParameter('agent', $agent);
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
            ->andWhere('agentEmploiType.emploiType = :emploiType')->setParameter('emploiType', $emploiType);
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

    /**
     * @param Agent[] $agents
     * @return array
     */
    public function generateRecensementByEmploiType(array $agents): array
    {
        $agent_ids = array_map(function (Agent $agent) {
            return $agent->getId();
        }, $agents);
        $params = ['agent_ids' => $agent_ids];

        $sql = <<<EOS
select emploitype_id, count(DISTINCT acet.agent_id) as count from agent_carriere_emploitype acet
where true
  and coalesce(acet.date_debut, now()) <= now()
  and coalesce(acet.date_fin, now()) >= now()
  and acet.deleted_on IS NULL
  and acet.agent_id in (:agent_ids)
group by acet.emploitype_id
EOS;

        $tmp = $this->getSqlHelperService()->executeQuery($sql, $params, ['agent_ids' => Connection::PARAM_STR_ARRAY]);
        $dictionnaire = [];
        foreach ($tmp as $row) {
            $dictionnaire[$row['emploitype_id']] = $row['count'];
        }
        return $dictionnaire;
    }

    /**
     * @param EmploiType $emploiType
     * @param Agent[] $agents
     * @return AgentGrade[]
     */
    public function generateDictionnaireWithEmploiType(EmploiType $emploiType, array $agents): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentEmploiType.agent in (:agents)')->setParameter('agents', $agents)
            ->andWhere('agentEmploiType.emploiType = :emploiType')->setParameter('emploiType', $emploiType);
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}