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
     * @param integer $sourceId
     * @return self
     */
    public function setSourceId($sourceId);

    /** @return integer */
    public function getSourceId();
}