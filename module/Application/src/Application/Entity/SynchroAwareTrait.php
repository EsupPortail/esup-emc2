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
        $splits = explode("::", $name);
        if (count($splits) === 1) return $this->$name;
        if (count($splits) === 2) {
            $accesseur1 = $splits[0];
            $valeur_ = $this->$accesseur1;
            if ($valeur_ === null) return null;
            $accesseur2 = $splits[1];
            $valeur = $valeur_->get($accesseur2);
            return $valeur;
        }
        return null;
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