<?php

namespace Application\Service;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Structure;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use UnicaenEtat\Entity\Db\Etat;

trait DecoratorTrait {

    /**
     * @param QueryBuilder $qb
     * @param string $alias
     * @param DateTime|null $date
     * @return QueryBuilder
     */
    public function decorateWithActif(QueryBuilder $qb, string $alias, ?DateTime $date = null) : QueryBuilder
    {
        if ($date === null) $date = new DateTime();
        $qb = $qb->andWhere($alias.'.dateDebut IS NULL OR '.$alias.'.dateDebut <= :now')
            ->andWhere($alias.'.dateFin IS NULL OR '.$alias.'.dateFin >= :now')
            ->setParameter('now', $date);
        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $alias
     * @param Agent|null $agent
     * @return QueryBuilder
     */
    public function decorateWithAgent(QueryBuilder $qb, string $alias, ?Agent $agent = null) : QueryBuilder
    {
        $qb = $qb
            ->addSelect('agent')->join($alias.'.agent', 'agent')
        ;

        if ($agent !== null) {
            $qb = $qb->andWhere($alias . '.agent = :agent')
                ->setParameter('agent', $agent);
        }
        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $alias
     * @param Structure|null $structure
     * @return QueryBuilder
     */
    public function decorateWithStructure(QueryBuilder $qb, string $alias, ?Structure $structure = null) : QueryBuilder
    {
        $qb = $qb
            ->addSelect('structure')->join($alias.'.structure', 'structure')
        ;

        if ($structure !== null) {
            $qb = $qb->andWhere($alias . '.structure = :structure')
                ->setParameter('structure', $structure);
        }
        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $alias
     * @param Etat|null $etat
     * @return QueryBuilder
     */
    public function decorateWithEtat(QueryBuilder $qb, string $alias, ?Etat $etat = null) : QueryBuilder
    {
        $qb = $qb
            ->addSelect('etat')->join($alias.'.etat', 'etat')
            ->addSelect('etattype')->leftjoin('etat.type', 'etattype')
        ;

        if ($etat !== null) {
            $qb = $qb->andWhere($alias . '.etat = :etat')
                ->setParameter('etat', $etat);
        }
        return $qb;
    }
}