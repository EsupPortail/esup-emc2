<?php

namespace Carriere\Entity\Db\Trait;


use Carriere\Entity\Db\Niveau;

trait HasNiveauCarriereTrait {

    private ?Niveau $niveauCarriere = null;

    public function getNiveauCarriere(): ?Niveau
    {
        return $this->niveauCarriere;
    }

    public function setNiveauCarriere(?Niveau $niveauCarriere): void
    {
        $this->niveauCarriere = $niveauCarriere;
    }

}