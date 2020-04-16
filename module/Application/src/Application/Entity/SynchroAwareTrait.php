<?php

namespace Application\Entity;

use DateTime;

trait SynchroAwareTrait {

    /** @var DateTime */
    private $synchro;
    /** @var DateTime */
    private $histo;

    /**
     * @return DateTime
     */
    public function getSynchro()
    {
        return $this->synchro;
    }

    /**
     * @param DateTime $synchro
     * @return self
     */
    public function setSynchro($synchro)
    {
        $this->synchro = $synchro;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getHisto()
    {
        return $this->histo;
    }

    /**
     * @param DateTime $histo
     * @return self
     */
    public function setHisto($histo)
    {
        $this->histo = $histo;
        return $this;
    }
}