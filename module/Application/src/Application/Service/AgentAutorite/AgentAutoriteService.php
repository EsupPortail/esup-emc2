<?php

namespace Application\Service\AgentAutorite;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Service\Agent\AgentServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class AgentAutoriteService
{
    use EntityManagerAwareTrait;
    use AgentServiceAwareTrait;

    /** GESTION DE L'ENTITE *******************************************************************************************/

    public function create(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $this->getEntityManager()->persist($agentAutorite);
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    public function update(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    public function historise(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $agentAutorite->historiser();
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    public function restore(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $agentAutorite->dehistoriser();
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    public function delete(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $this->getEntityManager()->remove($agentAutorite);
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentAutorite::class)->createQueryBuilder('agentautorite')
            ->join('agentautorite.agent', 'agent')->addSelect('agent')
            ->join('agentautorite.autorite', 'autorite')->addSelect('autorite')
        ;
        return $qb;
    }

    public function getAgentAutorite(?int $id) : ?AgentAutorite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentautorite.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs AgentAutorite partagent le même id ['.$id.']');
        }
        return $result;
    }

    public function getRequestedAgentAutorite(AbstractActionController $controller, string $param='agent-autorite') : ?AgentAutorite
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getAgentAutorite($id);
        return $result;
    }

    /** @return AgentAutorite[] */
    public function getAgentsAutorites(bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('agentautorite.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentautorite.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentAutorite[] */
    public function getAgentsAutoritesByAgent(Agent $agent, bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentautorite.agent = :agent')->setParameter('agent', $agent)
            ->orderBy('agentautorite.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentautorite.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentAutorite[] */
    public function getAgentsAutoritesByAutorite(Agent $autorite, bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentautorite.autorite = :autorite')->setParameter('autorite', $autorite)
            ->orderBy('agentautorite.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentautorite.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createAgentAutorite(Agent $agent, Agent $autorite) : AgentAutorite
    {
        $agentAutorite = new AgentAutorite();
        $agentAutorite->setAgent($agent);
        $agentAutorite->setAutorite($autorite);
        $this->create($agentAutorite);
        return $agentAutorite;
    }

    /**
     * @param array $declaration
     * @return array
     */
    public function createAgentAutoriteWithArray(array $declaration) : array
    {
        $result = [];
        [$agentId, $autoriteIdArray] = $declaration;
        $agent = $this->getAgentService()->getAgent($agentId);
        if ($agent === null) throw new RuntimeException("Aucun agent de connu avec l'identifiant [".$agentId."]");
        $autorites = [];
        foreach ($autoriteIdArray as $item) {
            $autorite = $this->getAgentService()->getAgent($item);
            if ($autorite === null) throw new RuntimeException("Aucun agent de connu avec l'identifiant [".$autorite."]");
            $autorites[] = $autorite;
        }

        foreach ($agent->getAutorites() as $current) $this->historise($current);
        foreach ($autorites as $autorite) {
            $agentautorite = $this->createAgentAutorite($agent,$autorite);
            $result[] = $agentautorite;
        }
        return $result;
    }
}