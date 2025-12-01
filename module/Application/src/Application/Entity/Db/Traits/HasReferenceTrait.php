<?php

namespace Application\Entity\Db\Traits;

use Metier\Entity\Db\Referentiel;

trait HasReferenceTrait
{
    private ?Referentiel $referentiel = null;
    private ?string $reference = null;

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): void
    {
        $this->referentiel = $referentiel;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    public function printReference(): string
    {
        $texte  = "<span class='badge'>";
        $texte .= ($this->getReferentiel()?$this->getReferentiel()->getLibelleCourt():"Aucun référentiel");
        $texte .= " - ";
        $texte .= ($this->getReference()??"Aucune référence");
        $texte .= "</span>";
        return $texte;
    }
}
