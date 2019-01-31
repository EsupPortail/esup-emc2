<?php

namespace Octopus\Service\Structure;

use Doctrine\ORM\NonUniqueResultException;
use Octopus\Entity\Db\Structure;
use Octopus\Entity\Db\StructureType;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class StructureService {
    use EntityManagerAwareTrait;

    /**
     * @param string $order
     * @return StructureType[]
     */
    public function getStructuresTypes($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(StructureType::class)->createQueryBuilder('type');

        if ($order) $qb = $qb->orderBy('type.'.$order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return StructureType
     */
    public function getStructureType($id)
    {
        $qb = $this->getEntityManager()->getRepository(StructureType::class)->createQueryBuilder('type')
            ->andWhere('type.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs StructureType partagent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param string $term
     * @return StructureType[]
     */
    public function getStructuresTypesByTerm($term)
    {
        $qb = $this->getEntityManager()->getRepository(StructureType::class)->createQueryBuilder('type')
            ->andWhere('type.libelle LIKE :search')
            ->setParameter('search', '%'.$term.'%')
            ->orderBy('type.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /******************************************************************************************************************/

    /**
     * @param string $order
     * @return Structure[]
     */
    public function getStructures($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('type');

        if ($order) $qb = $qb->orderBy('type.'.$order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Structure
     */
    public function getStructure($id)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('type')
            ->andWhere('type.id = :id')
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
     * @return Structure[]
     */
    public function getStructuresByTerm($term)
    {
        $qb = $this->getEntityManager()->getRepository(Structure::class)->createQueryBuilder('type')
            ->andWhere('type.libelleCourt LIKE :search')
            ->setParameter('search', '%'.$term.'%')
            ->orderBy('type.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}