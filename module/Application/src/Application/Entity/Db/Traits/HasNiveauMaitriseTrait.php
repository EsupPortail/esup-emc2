<?php

namespace Application\Entity\Db\Traits;

use Element\Entity\Db\Niveau;

trait HasNiveauMaitriseTrait {

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
     * @return HasNiveauMaitriseTrait
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
     * @return HasNiveauMaitriseTrait
     */
    public function setClef(?bool $clef): self
    {
        $this->clef = $clef;
        return $this;
    }


}