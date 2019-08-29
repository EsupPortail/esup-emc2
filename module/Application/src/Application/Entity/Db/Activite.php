<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Activite
{
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;
    /** @var ArrayCollection */
    private $applications;
    /** @var ArrayCollection */
    private $formations;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->formations = new ArrayCollection();
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
     * @return Activite
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
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
     * @return Activite
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Application[]
     */
    public function getApplications()
    {
        return $this->applications->toArray();
    }

    /**
     * @param Application $application
     * @return Activite
     */
    public function addApplication($application)
    {
        $this->applications->add($application);
        return $this;
    }

    /**
     * @param Application $application
     * @return Activite
     */
    public function removeApplication($application)
    {
        $this->applications->removeElement($application);
        return $this;
    }

    /**
     * @param Application $application
     * @return boolean
     */
    public function hasApplication($application)
    {
        return $this->applications->contains($application);
    }

    /**
     * @return Formation[]
     */
    public function getFormations()
    {
        return $this->formations->toArray();
    }

    /**
     * @param Formation $formation
     * @return Activite
     */
    public function addFormation($formation)
    {
        $this->formations->add($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return Activite
     */
    public function removeFormation($formation)
    {
        $this->formations->removeElement($formation);
        return $this;
    }
}