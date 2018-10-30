<?php

namespace Application\Service\Affectation;

use Application\Entity\Db\Affectation;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class AffectationService {
    use EntityManagerAwareTrait;

    /**
     * @param string $order
     * @return Affectation[]
     */
    public function getAffections($order = 'libelle')
    {
        $qb = $this->getEntityManager()->getRepository(Affectation::class)->createQueryBuilder('affectation')
            ->addOrderBy('affectation.'.$order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAffectation($id)
    {
        $qb = $this->getEntityManager()->getRepository(Affectation::class)->createQueryBuilder('affectation')
            ->andWhere('affectation.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs affectations partagent le même identifiant [".$id."]");
        }
        return $result;
    }
}