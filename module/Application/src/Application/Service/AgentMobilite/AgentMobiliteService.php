<?php

namespace Application\Service\AgentMobilite;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentMobilite;
use Application\Service\Agent\AgentServiceAwareTrait;
use Carriere\Entity\Db\Mobilite;
use Carriere\Service\Mobilite\MobiliteServiceAwareTrait;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;

class AgentMobiliteService
{
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;
    use MobiliteServiceAwareTrait;

    /** GESTION DE L'ENTITE *******************************************************************************************/

    public function create(AgentMobilite $agentMobilite): AgentMobilite
    {
        $this->getObjectManager()->persist($agentMobilite);
        $this->getObjectManager()->flush($agentMobilite);
        return $agentMobilite;
    }

    public function update(AgentMobilite $agentMobilite): AgentMobilite
    {
        $this->getObjectManager()->flush($agentMobilite);
        return $agentMobilite;
    }

    public function historise(AgentMobilite $agentMobilite): AgentMobilite
    {
        $agentMobilite->historiser();
        $this->getObjectManager()->flush($agentMobilite);
        return $agentMobilite;
    }

    public function restore(AgentMobilite $agentMobilite): AgentMobilite
    {
        $agentMobilite->dehistoriser();
        $this->getObjectManager()->flush($agentMobilite);
        return $agentMobilite;
    }

    public function delete(AgentMobilite $agentMobilite): AgentMobilite
    {
        $this->getObjectManager()->remove($agentMobilite);
        $this->getObjectManager()->flush($agentMobilite);
        return $agentMobilite;
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentMobilite::class)->createQueryBuilder('agentmobilite')
            ->join('agentmobilite.agent', 'agent')->addSelect('agent')
            ->join('agentmobilite.mobilite', 'mobilite')->addSelect('mobilite')
        ;
        return $qb;
    }

    public function getAgentMobilite(?int $id): ?AgentMobilite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmobilite.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs ['.AgentMobilite::class.'] partagent le même id [' . $id . ']',0,$e);
        }
        return $result;
    }

    public function getRequestedAgentMobilite(AbstractActionController $controller, string $param = 'agent-mobilite'): ?AgentMobilite
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getAgentMobilite($id);
        return $result;
    }

    /** @return AgentMobilite[] */
    public function getAgentsMobilites(bool $histo = false, string $champ = 'id', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('agentmobilite.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentmobilite.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentMobilite[] */
    public function getAgentsMobilitesByAgent(Agent $agent, bool $histo = false, string $champ = 'id', $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmobilite.agent = :agent')->setParameter('agent', $agent)
            ->orderBy('agentmobilite.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentmobilite.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentMobilite[] */
    public function getAgentsAutoritesByMobilite(Mobilite $mobilite, bool $histo = false, string $champ = 'id', $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentmobilite.mobilite = :mobilite')->setParameter('autorite', $mobilite)
            ->orderBy('agentmobilite.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentmobilite.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createAgentMobilite(Agent $agent, Mobilite $mobilite): AgentMobilite
    {
        $agentMobilite = new AgentMobilite();
        $agentMobilite->setAgent($agent);
        $agentMobilite->setMobilite($mobilite);
        $this->create($agentMobilite);
        return $agentMobilite;
    }

}