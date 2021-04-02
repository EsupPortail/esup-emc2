<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\MaitriseNiveau;

trait HasNiveauMaitriseTrait {

    /** @var MaitriseNiveau */
    private $niveau;
    /** @var bool */
    private $clef;

    /**
     * @return MaitriseNiveau|null
     */
    public function getNiveauMaitrise(): ?MaitriseNiveau
    {
        return $this->niveau;
    }

    /**
     * @param MaitriseNiveau|null $niveau
     * @return HasNiveauMaitriseTrait
     */
    public function setNiveauMaitrise(?MaitriseNiveau $niveau): self
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