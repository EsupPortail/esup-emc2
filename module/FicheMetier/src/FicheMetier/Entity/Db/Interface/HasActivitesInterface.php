<?php

namespace FicheMetier\Entity\Db\Interface;

use Doctrine\ORM\QueryBuilder;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Entity\Db\ActiviteElement;

interface HasActivitesInterface
{
    /** @return ActiviteElement[] */
    public function getActivites(bool $withHisto = false): array;
    public function addActivite(ActiviteElement $element): void;
    public function removeActivite(ActiviteElement $element): void;
    public function clearActivites(): void;
    public function hasActivite(Activite $activite): bool;

    public function getActivitesAsList(bool $withHisto = false): string;


    static public function decorateWithActivite(QueryBuilder $qb, string $entityName,  ?Activite $activite = null, bool $withHisto = false) : QueryBuilder;

}