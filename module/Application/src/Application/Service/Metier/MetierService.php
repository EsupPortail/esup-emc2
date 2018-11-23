<?php

namespace  Application\Service\Metier;

use Application\Entity\Db\Metier;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class MetierService {
    use EntityManagerAwareTrait;

    /**
     * @param string $order
     * @return Metier[]
     */
    public function getMetiers($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
        ;

        if ($order !== null) {
            $qb = $qb->orderBy('metier.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
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
            throw new RuntimeException("Plusieurs métiers partagent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param Metier $metier
     * @return Metier mixed
     */
    public function create($metier)
    {
        $this->getEntityManager()->persist($metier);
        try {
            $this->getEntityManager()->flush($metier);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du métier.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier mixed
     */
    public function update($metier)
    {
        try {
            $this->getEntityManager()->flush($metier);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de la mise à jour du métier.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     */
    public function delete($metier)
    {
        $this->getEntityManager()->remove($metier);
        try {
            $this->getEntityManager()->flush($metier);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de la suppression du métier.", $e);
        }
    }
}