<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Activite
{
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;
    /** @var ArrayCollection */
    private $libelles;
    /** @var string */
    private $description;
    /** @var ArrayCollection */
    private $descriptions;
    /** @var ArrayCollection */
    private $applications;
    /** @var ArrayCollection */
    private $competences;
    /** @var ArrayCollection */
    private $formations;

    public function __construct()
    {
        $this->libelles = new ArrayCollection();
        $this->descriptions = new ArrayCollection();
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

    /** LIBELLE *******************************************************************************************************/

    public function getLibelleField()
    {
        return $this->libelle;
    }

    /**
     * @return ActiviteLibelle
     */
    public function getCurrentActiviteLibelle()
    {
        $current = null;
        /** @var ActiviteLibelle $activiteLibelle */
        foreach ($this->libelles as $activiteLibelle) {
            if ($activiteLibelle->estNonHistorise()) return $activiteLibelle;
        }
        return null;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        $libelle = null;
        /** @var ActiviteLibelle $instance */
        foreach ($this->libelles as $instance) {
            if ($instance->getHistoDestruction() === null) {
                if ($libelle === null) return $instance->getLibelle();
                else return "<span class='probleme'><strong>PLUSIEURS LIBELLÉS ACTIFS !</strong></span>";
            }
        }
        if ($this->libelle !== null) return "<span class='probleme'>".$this->libelle."</span>";
        return  "<span class='probleme'><strong>AUCUN LIBELLÉ !</strong></span>";
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

    /** DESCRIPTION ***************************************************************************************************/

    public function getDescriptionField()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return ActiviteDescription[]
     */
    public function getDescriptions()
    {
        $descriptions = [];
        /** @var ActiviteDescription $activiteDescription */
        foreach ($this->descriptions as $activiteDescription) {
            if ($activiteDescription->estNonHistorise()) $descriptions[] = $activiteDescription;
        }
        return $descriptions;
    }

    /**
     * @return Activite
     */
    public function clearDescriptions()
    {
        $this->descriptions->clear();
        return $this;
    }

    /**
     * @param string description
     * @return Activite
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /** APPLICATIONS **************************************************************************************************/

    /**
     * @return ArrayCollection (ActiviteApplication)
     */
    public function getApplicationsCollection()
    {
        return $this->applications;
    }

    /**
     * @return Application[]
     */
    public function getApplications()
    {
        $applications = [];
        /** @var ActiviteApplication $activiteApplication */
        foreach ($this->applications as $activiteApplication) {
            if ($activiteApplication->estNonHistorise()) $applications[] = $activiteApplication->getApplication();
        }
        return $applications;
    }

    /**
     * @param Application $application
     * @return boolean
     */
    public function hasApplication(Application $application)
    {
        /** @var ActiviteApplication $activiteApplication */
        foreach ($this->applications as $activiteApplication) {
            if ($activiteApplication->estNonHistorise() AND $activiteApplication->getApplication() === $application) return true;
        }
        return false;
    }

    /** COMPETENCES ***************************************************************************************************/

    /**
     * @return ArrayCollection (ActiviteCompetence)
     */
    public function getCompetencesCollection()
    {
        return $this->competences;
    }

    /**
     * @return Competence[]
     */
    public function getCompetences()
    {
        $competences = [];
        /** @var ActiviteCompetence $activiteCompetence */
        foreach ($this->competences as $activiteCompetence) {
            if ($activiteCompetence->estNonHistorise()) $competences[] = $activiteCompetence->getCompetence();
        }
        return $competences;
    }

    /**
     * @param Competence $competence
     * @return boolean
     */
    public function hasCompetence(Competence $competence)
    {
        /** @var ActiviteCompetence $activiteCompetence */
        foreach ($this->competences as $activiteCompetence) {
            if ($activiteCompetence->estNonHistorise() AND $activiteCompetence->getCompetence() === $competence) return true;
        }
        return false;
    }

    /** FORMATIONS ****************************************************************************************************/

    /**
     * @return ArrayCollection (ActiviteFormation)
     */
    public function getFormationsCollection()
    {
        return $this->formations;
    }

    /**
     * @return Formation[]
     */
    public function getFormations()
    {
        $formations = [];
        /** @var ActiviteFormation $activiteFormation */
        foreach ($this->formations as $activiteFormation) {
            if ($activiteFormation->estNonHistorise()) $formations[] = $activiteFormation->getFormation();
        }
        return $formations;
    }

    /**
     * @param Formation $formation
     * @return boolean
     */
    public function hasFormation(Formation $formation)
    {
        /** @var ActiviteFormation $activiteFormation */
        foreach ($this->formations as $activiteFormation) {
            if ($activiteFormation->estNonHistorise() AND $activiteFormation->getFormation() === $formation) return true;
        }
        return false;
    }

}