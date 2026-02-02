<?php

namespace Element\Entity\Db\Traits;

use Element\Entity\Db\NiveauMaitrise;

trait HasNiveauMaitriseTrait {

    private ?NiveauMaitrise $niveau = null;
    private bool $clef = false;

    public function getNiveauMaitrise(): ?NiveauMaitrise
    {
        return $this->niveau;
    }

    public function setNiveauMaitrise(?NiveauMaitrise $niveau): void
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