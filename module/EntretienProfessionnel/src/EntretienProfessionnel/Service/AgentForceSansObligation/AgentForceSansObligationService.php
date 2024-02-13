<?php

namespace EntretienProfessionnel\Service\AgentForceSansObligation;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Entity\Db\Campagne;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class AgentForceSansObligationService {
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;

    /** Gestion des entités **************************************************************/

    public function create(AgentForceSansObligation $agentForceSansObligation) : AgentForceSansObligation
    {
        $this->getObjectManager()->persist($agentForceSansObligation);
        $this->getObjectManager()->flush($agentForceSansObligation);
        return $agentForceSansObligation;
    }

    public function update(AgentForceSansObligation $agentForceSansObligation) : AgentForceSansObligation
    {
        $this->getObjectManager()->flush($agentForceSansObligation);
        return $agentForceSansObligation;
    }

    public function historise(AgentForceSansObligation $agentForceSansObligation) : AgentForceSansObligation
    {
        $agentForceSansObligation->historiser();
        $this->getObjectManager()->flush($agentForceSansObligation);
        return $agentForceSansObligation;
    }

    public function restore(AgentForceSansObligation $agentForceSansObligation) : AgentForceSansObligation
    {
        $agentForceSansObligation->dehistoriser();
        $this->getObjectManager()->flush($agentForceSansObligation);
        return $agentForceSansObligation;
    }

    public function delete(AgentForceSansObligation $agentForceSansObligation) : AgentForceSansObligation
    {
        $this->getObjectManager()->remove($agentForceSansObligation);
        $this->getObjectManager()->flush($agentForceSansObligation);
        return $agentForceSansObligation;
    }

    /** Requetage *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentForceSansObligation::class)->createQueryBuilder('agentForceSansObligation')
            ->leftJoin('agentForceSansObligation.agent', 'agent')->addSelect('agent')
            ->leftJoin('agentForceSansObligation.campagne', 'campagne')->addSelect('campagne')
        ;
        return $qb;
    }

    public function getAgentForceSansObligation(?int $id) : ?AgentForceSansObligation
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentForceSansObligation.id = :id')->setParameter('id',$id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".AgentForceSansObligation::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedAgentForceSansObligation(AbstractActionController $controller, string $param='agent-force-sans-obligation') : ?AgentForceSansObligation
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getAgentForceSansObligation($id);
    }

    /**
     * @return AgentForceSansObligation[]
     */
    public function getAgentsForcesSansObligation(string $champ = 'id', string $ordre = 'ASC', bool $withHisto = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('agentForceSansObligation.' . $champ, $ordre);
        if (!$withHisto) $qb = $qb->andWhere('agentForceSansObligation.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentForceSansObligation[] */
    public function getAgentsForcesSansObligationByCampagne(Campagne $campagne, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentForceSansObligation.campagne = :campagne')->setParameter('campagne', $campagne)
        ;
        if (!$withHisto) $qb = $qb->andWhere('agentForceSansObligation.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    //TODO getWithAgent
    //TODO getWithAgents
    //TODO getWithFiltre

    /** Façade ********************************************************************************************************/

    public function createWith(Agent $agent, Campagne $campagne, ?string $raison = null) : AgentForceSansObligation
    {
        $agentForceSansObligation = new AgentForceSansObligation();
        $agentForceSansObligation->setAgent($agent);
        $agentForceSansObligation->setCampagne($campagne);
        if ($raison !== null) $agentForceSansObligation->setRaison($raison);

        $this->create($agentForceSansObligation);
        return $agentForceSansObligation;
    }



}