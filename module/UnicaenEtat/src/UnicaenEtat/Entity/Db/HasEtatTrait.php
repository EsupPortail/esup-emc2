<?php

namespace UnicaenEtat\Entity\Db;

use Doctrine\ORM\QueryBuilder;

trait HasEtatTrait {

    private $etat;

    /**
     * @return Etat|null
     */
    public function getEtat() : ?Etat
    {
        return $this->etat;
    }

    /**
     * @param Etat|null $etat
     * @return HasEtatTrait
     */
    public function setEtat(?Etat $etat)
    {
        $this->etat = $etat;
        return $this;
    }

    /**
     * @param string $etatCode
     * @return boolean
     */
    public function isEtat(string $etatCode) : bool
    {
        if ($this->etat === null) return false;
        return ($this->etat->getCode() === $etatCode);
    }

    /** Decorateur ****************************************************************************************************/

    /**
     * @param QueryBuilder $qb
     * @param string $entityName
     * @param Etat|null $etat
     * @return QueryBuilder
     */
    static public function decorateWithEtat(QueryBuilder $qb, string $entityName,  ?Etat $etat = null) : QueryBuilder
    {
        $qb = $qb
            ->leftJoin($entityName . '.etat', 'etat')->addSelect('etat')
            ->leftJoin('etat.type', 'type')->addSelect('type')
        ;

        if ($etat !== null) {
            $qb = $qb->andWhere($entityName . '.etat = :etat')
                    ->setParameter('etat', $etat);
        }

        return $qb;
    }

}