<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\DbImportableAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class Grade {
    use DbImportableAwareTrait;

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
    /** @var ArrayCollection (AgentGrade) */
    private $agentGrades;

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

    /**
     * @return AgentGrade[]
     */
    public function getAgentGrades()
    {
        return $this->agentGrades->toArray();
    }

    public function __toString()
    {
        return $this->getLibelleCourt();
    }

    /**
     * @return string
     */
    public function generateTooltip()
    {
        $text  = "";
        $text .= "Libelle court : <strong>". $this->getLibelleCourt() . "</strong>";
        $text .= "<br/>";
        $text .= "Libelle long : <strong>". $this->getLibelleLong() . "</strong>";
        $text .= "<br/>";
        $text .= "Code : <strong>". $this->getCode() . "</strong>";
        return $text;
    }
}