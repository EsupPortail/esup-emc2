<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Utilisateur\Entity\HistoriqueAwareTrait;

class Competence {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $precision;
    /** @var string */
    private $description;
    /** @var CompetenceType */
    private $type;
    /** @var CompetenceTheme */
    private $theme;

    /** @var ArrayCollection (FicheMetier) */
    private $fiches;
    /** @var ArrayCollection (Activite) */
    private $activites;

    public function __construct()
    {
        $this->fiches = new ArrayCollection();
        $this->activites = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Competence
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param string $precision
     * @return Competence
     */
    public function setPrecision($precision)
    {
        $this->precision = $precision;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Competence
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return CompetenceType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param CompetenceType $type
     * @return Competence
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param mixed $theme
     * @return Competence
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return FicheMetier[]
     */
    public function getFichesMetiers()
    {
        return $this->fiches->toArray();
    }

    /**
     * @return Activite[]
     */
    public function getActivites()
    {
        return $this->activites->toArray();
    }
}