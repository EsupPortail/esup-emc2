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

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name) {
        return $this->$name;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return self
     */
    public function set($name, $value) {
        $this->$name = $value;
        return $this;
    }
}