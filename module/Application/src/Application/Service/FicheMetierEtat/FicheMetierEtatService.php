<?php

namespace Application\Service\FicheMetierEtat;

use Application\Entity\Db\FicheMetierEtat;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class FicheMetierEtatService {
    use EntityManagerAwareTrait;

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetierEtat::class)->createQueryBuilder('etat')
            ;
        return $qb;

    }

    public function getEtats($champ='id', $ordre='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('etat.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getEtatsAsOption()
    {
        $etats = $this->getEtats();
        $array = [];
        foreach ($etats as $etat) {
            $array[$etat->getId()] = $etat->getLibelle();
        }
        return $array;
    }

    /**
     * @param $id
     * @return FicheMetierEtat
     */
    public function getEtat($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FicheMetierEtat partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param string $code
     * @return FicheMetierEtat
     */
    public function getEtatByCode($code)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.code = :code')
            ->setParameter('code', $code)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FicheMetierEtat partagent le même code [".$code."]");
        }
        return $result;
    }
}