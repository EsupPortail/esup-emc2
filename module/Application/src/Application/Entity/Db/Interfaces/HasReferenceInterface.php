<?php

namespace Application\Entity\Db\Interfaces;

use Metier\Entity\Db\Referentiel;

interface HasReferenceInterface
{
    public function getReferentiel(): ?Referentiel;
    public function setReferentiel(?Referentiel $referentiel): void;
    public function getReference(): ?string;
    public function setReference(?string $reference): void;
    public function printReference(): string;
}
