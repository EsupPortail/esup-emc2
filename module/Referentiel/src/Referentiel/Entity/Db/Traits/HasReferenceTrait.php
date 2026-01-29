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

    /** Les modes sont : modal, lien **/
    public function printReference(?string $mode = null, ?string $url = null, bool $isAllowed = true): string
    {
        $referentiel = $this->getReferentiel();
        $label  = ($referentiel?$referentiel->getLibelleCourt():"Aucun référentiel");
        $label .= " - ";
        $label .= ($this->getReference()??"Aucune référence");
        $span = "<span class='badge' style='background:".($referentiel?$referentiel->getCouleur():"grey")."'>" . $label . "</span>";

        if (!$isAllowed OR $mode === null) {
            return $span;
        } else {
            return "TODO";
        }

        $texte  = "<span class='badge' style='background:".($referentiel?$referentiel->getCouleur():"grey")."'>";
        $texte .= $label;
        $texte .= "</span>";
        return $texte;
    }
}
