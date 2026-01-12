<?php

namespace Referentiel\Entity\Db\Traits;

use Referentiel\Entity\Db\Referentiel;

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

    public function printReference(bool $withHTML = true): string
    {
        $referentiel = $this->getReferentiel();
        $label  = ($referentiel?$referentiel->getLibelleCourt():"Aucun référentiel");
        $label .= " - ";
        $label .= ($this->getReference()??"Aucune référence");
        if (!$withHTML) return $label;
        $texte  = "<span class='badge' style='background:".($referentiel?$referentiel->getCouleur():"grey")."'>";
        $texte .= $label;
        $texte .= "</span>";
        return $texte;
    }
}
