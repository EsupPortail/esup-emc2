<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\DbImportableAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class Correspondance  {
    use DbImportableAwareTrait;

    /** @var integer */
    private $source_id;
    /** @var string */
    private $categorie;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var DateTime */
    private $histo;
    /** @var ArrayCollection (AgentGrade) */
    private $agentGrades;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->source_id;
    }

    /**
     * @return int
     */
    public function getSourceId()
    {
        return $this->source_id;
    }

    /**
     * @param int $source_id
     * @return Correspondance
     */
    public function setSourceId($source_id)
    {
        $this->source_id = $source_id;
        return $this;
    }


    /**
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param string $categorie
     * @return Correspondance
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
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
     * @return Correspondance
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
     * @return Correspondance
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;
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
     * @return Correspondance
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

    /**
     * @return string
     */
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
        return $text;
    }

}