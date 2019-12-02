<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Utilisateur\Entity\HistoriqueAwareTrait;

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
    private $competences;
    /** @var ArrayCollection */
    private $formations;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->competences = new ArrayCollection();
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

    /** APPLICATIONS **************************************************************************************************/

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
     * @return Activite
     */
    public function clearApplications()
    {
        $this->applications->clear();
        return $this;
    }

    /** COMPETENCES ***************************************************************************************************/

    /**
     * @return Competence[]
     */
    public function getCompetences()
    {
        return $this->competences->toArray();
    }

    /**
     * @param Competence $competence
     * @return Activite
     */
    public function addCompetence($competence)
    {
        $this->competences->add($competence);
        return $this;
    }

    /**
     * @param Competence $competence
     * @return Activite
     */
    public function removeCompetence($competence)
    {
        $this->competences->removeElement($competence);
        return $this;
    }

    /**
     * @param Competence $competence
     * @return boolean
     */
    public function hasCompetence($competence)
    {
        return $this->competences->contains($competence);
    }

    /**
     * @return Activite
     */
    public function clearCompetences()
    {
        $this->competences->clear();
        return $this;
    }

    /** FORMATIONS ****************************************************************************************************/

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

    /**
     * @param Formation $formation
     * @return boolean
     */
    public function hasFormation($formation)
    {
        return $this->formations->contains($formation);
    }

    /**
     * @return Activite
     */
    public function clearFormations()
    {
        $this->formations->clear();
        return $this;
    }
}