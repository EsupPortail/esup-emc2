<?php

namespace Application\Entity\Db;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class Corps
{
    use ImportableAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var string */
    private $categorie;
    /** @var DateTime */
    private $histo;
    /** @var integer */
    private $niveau;

    /** @var ArrayCollection (AgentGrade) */
    private $agentGrades;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Corps
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Corps
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return Corps
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
     * @return Corps
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;
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
     * @return Corps
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
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
     * @return Corps
     */
    public function setHisto($histo)
    {
        $this->histo = $histo;
        return $this;
    }

    /**
     * @return int
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @param int $niveau
     * @return Corps
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
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