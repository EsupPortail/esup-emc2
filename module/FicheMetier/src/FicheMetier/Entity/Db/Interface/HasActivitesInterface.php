<?php

namespace FicheMetier\Entity\Db\Interface;

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

}