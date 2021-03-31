<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CompetenceMaitrise;

trait HasNiveauMaitriseTrait {

    /** @var CompetenceMaitrise */
    private $niveau;
    /** @var bool */
    private $clef;

    /**
     * @return CompetenceMaitrise|null
     */
    public function getNiveauMaitrise(): ?CompetenceMaitrise
    {
        return $this->niveau;
    }

    /**
     * @param CompetenceMaitrise|null $niveau
     * @return HasNiveauMaitriseTrait
     */
    public function setNiveauMaitrise(?CompetenceMaitrise $niveau): self
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