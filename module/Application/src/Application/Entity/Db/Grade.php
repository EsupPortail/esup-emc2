<?php

namespace Application\Entity\Db;

use Application\Entity\SynchroAwareInterface;
use Application\Entity\SynchroAwareTrait;
use DateTime;

class Grade {
    use ImportableAwareTrait;

    /** @var string */
    private $id;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var string */
    private $code;
    /** @var DateTime */
    private $histo;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * @param string $libelleCourt
     * @return Grade
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * @param string $libelleLong
     * @return Grade
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Grade
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return Grade
     */
    public function setHisto($histo)
    {
        $this->histo = $histo;
        return $this;
    }

    public function __toString()
    {
        return $this->getLibelleCourt();
    }
}