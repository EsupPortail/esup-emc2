<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CompetenceMaitrise;

trait HasNiveauMaitriseTrait {

    /** @var CompetenceMaitrise */
    private $niveau;

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

}