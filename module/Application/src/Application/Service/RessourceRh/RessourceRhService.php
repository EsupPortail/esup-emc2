<?php

namespace Application\Service\RessourceRh;

use Application\Entity\Db\AgentStatus;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class RessourceRhService {
    use EntityManagerAwareTrait;

    /** AGENT STATUS **************************************************************************************************/

    /**
     * @param string $order
     * @return AgentStatus[]
     */
    public function getAgentStatusListe($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(AgentStatus::class)->createQueryBuilder('status');

        if ($order !== null) {
            $qb = $qb->orderBy('status.' . $order);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return AgentStatus
     */
    public function getAgentStatus($id)
    {
        $qb = $this->getEntityManager()->getRepository(AgentStatus::class)->createQueryBuilder('status')
            ->andWhere('status.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs status partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AgentStatus $status
     * @return AgentStatus
     */
    public function createAgentStatus($status)
    {
        $this->getEntityManager()->persist($status);
        try {
            $this->getEntityManager()->flush($status);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un status", $e);
        }
        return $status;
    }

    /**
     * @param AgentStatus $status
     * @return AgentStatus
     */
    public function updateAgentStatus($status)
    {
        try {
            $this->getEntityManager()->flush($status);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un status", $e);
        }
        return $status;
    }

    /**
     * @param AgentStatus $status
     */
    public function deleteAgentStatus($status)
    {
        $this->getEntityManager()->remove($status);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un status", $e);
        }
    }
}