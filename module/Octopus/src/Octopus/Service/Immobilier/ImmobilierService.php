<?php

namespace Octopus\Service\Immobilier;

use Doctrine\ORM\NonUniqueResultException;
use Octopus\Entity\Db\ImmobilierBatiment;
use Octopus\Entity\Db\ImmobilierLocal;
use Octopus\Entity\Db\ImmobilierNiveau;
use Octopus\Entity\Db\ImmobilierSite;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class ImmobilierService {
    use EntityManagerAwareTrait;

    /**
     * @param string $order
     * @return ImmobilierLocal[]
     */
    public function getImmobilierLocals($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierLocal::class)->createQueryBuilder('local');

        if($order) $qb = $qb->orderBy('local.' . $order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return ImmobilierLocal
     */
    public function getImmobilierLocal($id)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierLocal::class)->createQueryBuilder('local')
            ->andWhere('local.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Structure partagent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param string $term
     * @return ImmobilierLocal[]
     */
    public function getImmobilierLocalsByTerm($term)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierLocal::class)->createQueryBuilder('local')
            ->andWhere('local.libelle LIKE :search')
            ->setParameter('search', '%'.$term.'%')
            ->orderBy('type.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /******************************************************************************************************************/

    /**
     * @param string $order
     * @return ImmobilierNiveau[]
     */
    public function getImmobilierNiveaux($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierNiveau::class)->createQueryBuilder('local');

        if($order) $qb = $qb->orderBy('local.' . $order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return ImmobilierNiveau
     */
    public function getImmobilierNiveau($id)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierNiveau::class)->createQueryBuilder('local')
            ->andWhere('local.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Structure partagent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param string $term
     * @return ImmobilierNiveau[]
     */
    public function getImmobilierNiveauxByTerm($term)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierNiveau::class)->createQueryBuilder('local')
            ->andWhere('local.libelle LIKE :search')
            ->setParameter('search', '%'.$term.'%')
            ->orderBy('type.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /******************************************************************************************************************/

    /**
     * @param string $order
     * @return ImmobilierBatiment[]
     */
    public function getImmobilierBatiments($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierBatiment::class)->createQueryBuilder('local');

        if($order) $qb = $qb->orderBy('local.' . $order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return ImmobilierBatiment
     */
    public function getImmobilierBatiment($id)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierBatiment::class)->createQueryBuilder('local')
            ->andWhere('local.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Structure partagent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param string $term
     * @return ImmobilierBatiment[]
     */
    public function getImmobilierBatimentsByTerm($term)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierBatiment::class)->createQueryBuilder('local')
            ->andWhere('local.libelle LIKE :search')
            ->setParameter('search', '%'.$term.'%')
            ->orderBy('type.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /******************************************************************************************************************/

    /**
     * @param string $order
     * @return ImmobilierSite[]
     */
    public function getImmobilierSites($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierSite::class)->createQueryBuilder('local');

        if($order) $qb = $qb->orderBy('local.' . $order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return ImmobilierSite
     */
    public function getImmobilierSite($id)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierSite::class)->createQueryBuilder('local')
            ->andWhere('local.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Structure partagent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param string $term
     * @return ImmobilierSite[]
     */
    public function getImmobilierSitesByTerm($term)
    {
        $qb = $this->getEntityManager()->getRepository(ImmobilierSite::class)->createQueryBuilder('local')
            ->andWhere('local.libelle LIKE :search')
            ->setParameter('search', '%'.$term.'%')
            ->orderBy('type.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}