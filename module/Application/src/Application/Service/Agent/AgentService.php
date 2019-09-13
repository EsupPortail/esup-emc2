<?php

namespace Application\Service\Agent;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Structure;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class AgentService {
    use EntityManagerAwareTrait;

    /**
     * @param string $order
     * @return Agent[]
     */
    public function getAgents($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent');

        if ($order !== null) {
            $qb = $qb->orderBy('agent.' . $order);
        } else {
            $qb = $qb->orderBy('agent.nomUsuel, agent.prenom');
        }

        $result =  $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Agent
     */
    public function getAgent($id)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs agents partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Agent
     */
    public function getRequestedAgent($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $agent = $this->getAgent($id);
        return $agent;
    }

    /**
     * @param User $user
     * @return Agent
     */
    public function getAgentByUser($user)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.utilisateur = :user')
            ->setParameter('user', $user)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Agent liés au même User [".$user->getId()."]", $e);
        }
        return $result;
    }

    /**
     * @param Agent $agent
     * @return Agent
     */
    public function update($agent)
    {
        try {
            $this->getEntityManager()->flush($agent);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème a été recontré lors de la mise à jour de l'agent", $e);
        }
        return $agent;
    }

    /**
     * @param int $supannId
     * @return Agent
     */
    public function getAgentBySupannId($supannId)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->andWhere('agent.sourceName = :harp')
            ->andWhere('agent.sourceId = :supannId')
            ->setParameter('harp', 'HARP')
            ->setParameter('supannId', $supannId);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs agents partagent le même identifiant [".$supannId."]");
        }
        return $result;

    }

    /**
     * @param bool $active
     * @return array
     */
    public function getAgentsAsOption($active = true)
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->orderBy('agent.nomUsuel, agent.prenom');

        if ($active === true) {
            //TODO
        }

        /** @var Agent[] $result */
        $result = $qb->getQuery()->getResult();

        $agents = [];
        foreach ($result as $item) {
            $agents[$item->getId()] = $item->getDenomination();
        }
        return $agents;
    }

    /**
     * @param Structure $structure
     * @return Agent[]
     */
    public function getAgentsSansFichePosteByStructure($structure = null)
    {
        try {
            $today = new DateTime();
            $noEnd = DateTime::createFromFormat('d/m/Y H:i:s', '31/12/1999 00:00:00');
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la création des dates");
        }

        /** !!TODO!! faire le lien entre agent et fiche de poste */
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('statut')->join('agent.statuts', 'statut')
//            ->addSelect('fiche')->leftJoin('agent.fiche', 'fiche', 'WITH', 'fiche.agent = agent.id')
            ->andWhere('statut.fin >= :today OR statut.fin = :noEnd')
            ->andWhere('statut.administratif = :true')
//            ->andWhere('fiche.id IS NULL')
            ->setParameter('today', $today)
            ->setParameter('noEnd', $noEnd)
            ->setParameter('true', 'O')
        ;

        if ($structure !== null) {
            $qb = $qb->andWhere('statut.structure = :structure')
                ->setParameter('structure', $structure);
        }

        $result = $qb->getQuery()->getResult();

        return $result;

    }

    /**
     * @param Structure $structure
     * @return Agent[]
     */
    public function getAgentsByStructure(Structure $structure)
    {
        try {
            $today = new DateTime();
            $noEnd = DateTime::createFromFormat('d/m/Y H:i:s', '31/12/1999 00:00:00');
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la création des dates");
        }

        /** !!TODO!! faire le lien entre agent et fiche de poste */
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->andWhere('statut.fin >= :today OR statut.fin = :noEnd')
            ->andWhere('statut.administratif = :true')
            ->setParameter('today', $today)
            ->setParameter('noEnd', $noEnd)
            ->setParameter('true', 'O')

        ;

        if ($structure !== null) {
            $qb = $qb->andWhere('statut.structure = :structure')
                ->setParameter('structure', $structure);
        }

        $result = $qb->getQuery()->getResult();

        return $result;

    }
}