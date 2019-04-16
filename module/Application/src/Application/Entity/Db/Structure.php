<?php

namespace Application\Entity\Db;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use Utilisateur\Entity\Db\User;

class Structure {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var string */
    private $sigle;
    /** @var StructureType */
    private $type;
    /** @var DateTime */
    private $dateOuverture;
    /** @var DateTime */
    private $dateFermeture;
    /** @var string */
    private $source;
    /** @var string */
    private $idSource;
    /** @var string */
    private $description;

    /** @var ArrayCollection */
    private $gestionnaires;

    public function __construct()
    {
        $this->gestionnaires = new ArrayCollection();
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
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * @param string $libelleCourt
     * @return Structure
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
     * @return Structure
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;
        return $this;
    }

    /**
     * @return string
     */
    public function getSigle()
    {
        return $this->sigle;
    }

    /**
     * @param string $sigle
     * @return Structure
     */
    public function setSigle($sigle)
    {
        $this->sigle = $sigle;
        return $this;
    }

    /**
     * @return StructureType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param StructureType $type
     * @return Structure
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateOuverture()
    {
        return $this->dateOuverture;
    }

    /**
     * @param DateTime $dateOuverture
     * @return Structure
     */
    public function setDateOuverture($dateOuverture)
    {
        $this->dateOuverture = $dateOuverture;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateFermeture()
    {
        return $this->dateFermeture;
    }

    /**
     * @param DateTime $dateFermeture
     * @return Structure
     */
    public function setDateFermeture($dateFermeture)
    {
        $this->dateFermeture = $dateFermeture;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return Structure
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdSource()
    {
        return $this->idSource;
    }

    /**
     * @param string $idSource
     * @return Structure
     */
    public function setIdSource($idSource)
    {
        $this->idSource = $idSource;
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
     * @return Structure
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return User[]
     */
    public function getGestionnaires() {
        return $this->gestionnaires->toArray();
    }

    /**
     * @param User $gestionnaire
     */
    public function addGestionnaire($gestionnaire) {
        $this->gestionnaires->add($gestionnaire);
    }

    /**
     * @param User $gestionnaire
     */
    public function removeGestionnaire($gestionnaire) {
        $this->gestionnaires->removeElement($gestionnaire);
    }

    /**
     * @param User $gestionnaire
     * @return boolean
     */
    public function hasGestionnaire($gestionnaire) {
        return $this->gestionnaires->contains($gestionnaire);
    }

    public function __toString()
    {
        $text =  "";
        $text .= "[".$this->getType()->getCode()."] ";
        $text .= $this->getLibelleCourt();
        return $text;
    }
}