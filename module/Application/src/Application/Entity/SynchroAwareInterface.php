<?php

namespace Application\Entity;

use DateTime;

interface SynchroAwareInterface {

    /**
     * @param DateTime $date
     * @return self
     */
    public function setSynchro($date);

    /** @return Datetime */
    public function getSynchro();

    /** @return Datetime */
    public function getHisto();

    /**
     * @param DateTime $date
     * @return self
     */
    public function setHisto($date);

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name);

    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function set($name, $value);
}