<?php

namespace Application\Service\RessourceRh;

use Application\Entity\Db\AgentStatus;
use Application\Entity\Db\Corps;
use Application\Entity\Db\Correspondance;
use Application\Entity\Db\Metier;
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

    /** CORRESPONDANCE ************************************************************************************************/

    /**
     * @param string $order
     * @return Correspondance[]
     */
    public function getCorrespondances($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Correspondance::class)->createQueryBuilder('correspondance')
            ->orderBy('correspondance.reference', 'ASC')
        ;

        if ($order !== null) {
            $qb = $qb->addOrderBy('correspondance.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Correspondance
     */
    public function getCorrespondance($id)
    {
        $qb = $this->getEntityManager()->getRepository(Correspondance::class)->createQueryBuilder('correspondance')
            ->andWhere('correspondance.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs correpondances partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param Correspondance $correspondance
     * @return Correspondance
     */
    public function createCorrespondance($correspondance)
    {
        $this->getEntityManager()->persist($correspondance);
        try {
            $this->getEntityManager()->flush($correspondance);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'une correspondance", $e);
        }
        return $correspondance;
    }

    /**
     * @param Correspondance $correspondance
     * @return Correspondance
     */
    public function updateCorrespondance($correspondance)
    {
        try {
            $this->getEntityManager()->flush($correspondance);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'une correspondance", $e);
        }
        return $correspondance;
    }

    /**
     * @param Correspondance $correspondance
     */
    public function deleteCorrespondance($correspondance)
    {
        $this->getEntityManager()->remove($correspondance);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'une correspondance", $e);
        }
    }

    /** CORPS *********************************************************************************************************/

    /**
     * @param string $order
     * @return Corps[]
     */
    public function getCorpsListe($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Corps::class)->createQueryBuilder('corps')
        ;

        if ($order !== null) {
            $qb = $qb->addOrderBy('corps.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Corps
     */
    public function getCorps($id)
    {
        $qb = $this->getEntityManager()->getRepository(Corps::class)->createQueryBuilder('corps')
            ->andWhere('corps.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs corps partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param Corps $corps
     * @return Corps
     */
    public function createCorps($corps)
    {
        $this->getEntityManager()->persist($corps);
        try {
            $this->getEntityManager()->flush($corps);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un corps", $e);
        }
        return $corps;
    }

    /**
     * @param Corps $corps
     * @return Corps
     */
    public function updateCorps($corps)
    {
        try {
            $this->getEntityManager()->flush($corps);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un corps", $e);
        }
        return $corps;
    }

    /**
     * @param Corps $corps
     */
    public function deleteCorps($corps)
    {
        $this->getEntityManager()->remove($corps);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un corps", $e);
        }
    }

    /** CORPS *********************************************************************************************************/

    /**
     * @param string $order
     * @return Metier[]
     */
    public function getMetiers($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
        ;

        if ($order !== null) {
            $qb = $qb->addOrderBy('metier.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Metier
     */
    public function getMetier($id)
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->andWhere('metier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs métiers partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function createMetier($metier)
    {
        $this->getEntityManager()->persist($metier);
        try {
            $this->getEntityManager()->flush($metier);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un métier", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function updateMetier($metier)
    {
        try {
            $this->getEntityManager()->flush($metier);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un metier.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     */
    public function deleteMetier($metier)
    {
        $this->getEntityManager()->remove($metier);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un metier", $e);
        }
    }

}