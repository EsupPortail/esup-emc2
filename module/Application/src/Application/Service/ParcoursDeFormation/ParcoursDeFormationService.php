<?php

namespace Application\Service\ParcoursDeFormation;

use Application\Entity\Db\ParcoursDeFormation;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class ParcoursDeFormationService {
    use EntityManagerAwareTrait;

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(ParcoursDeFormation::class)->createQueryBuilder('parcours')
            ;
        return $qb;
    }

    public function getParcoursDeFormation($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('parcours.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ParcoursDeFormation partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }
}