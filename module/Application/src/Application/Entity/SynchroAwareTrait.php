<?php

namespace Application\Entity;

use DateTime;

trait SynchroAwareTrait {

    /** @var integer */
    private $source_id;
    /** @var DateTime */
    private $synchro;
    /** @var DateTime */
    private $histo;

    /**
     * @return int
     */
    public function getSourceId()
    {
        return $this->source_id;
    }

    /**
     * @param integer $id
     * @return self
     */
    public function setSourceId($id)
    {
        $this->source_id = $id;
        return $this;
    }

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