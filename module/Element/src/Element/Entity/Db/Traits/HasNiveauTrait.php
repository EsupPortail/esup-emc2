<?php

namespace Element\Entity\Db\Traits;

use Element\Entity\Db\Niveau;

trait HasNiveauTrait {

    private ?Niveau $niveau = null;
    private bool $clef = false;

    public function getNiveauMaitrise(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveauMaitrise(?Niveau $niveau): void
    {
        $this->niveau = $niveau;
    }

    public function isClef(): ?bool
    {
        return $this->clef;
    }

    public function setClef(?bool $clef): void
    {
        $this->clef = $clef;
    }


}