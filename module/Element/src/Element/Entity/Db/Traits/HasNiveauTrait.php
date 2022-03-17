<?php

namespace Element\Entity\Db\Traits;

use Element\Entity\Db\Niveau;

trait HasNiveauTrait {

    /** @var Niveau */
    private $niveau;
    /** @var bool */
    private $clef;

    /**
     * @return Niveau|null
     */
    public function getNiveauMaitrise(): ?Niveau
    {
        return $this->niveau;
    }

    /**
     * @param Niveau|null $niveau
     * @return HasNiveauTrait
     */
    public function setNiveauMaitrise(?Niveau $niveau): self
    {
        $this->niveau = $niveau;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isClef(): ?bool
    {
        return $this->clef;
    }

    /**
     * @param bool|null $clef
     * @return HasNiveauTrait
     */
    public function setClef(?bool $clef): self
    {
        $this->clef = $clef;
        return $this;
    }


}