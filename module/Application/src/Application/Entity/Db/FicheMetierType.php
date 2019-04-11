<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class FicheMetierType {

    /** @var int */
    private $id;
    /** @var Metier */
    private $metier;
    /** @var string */
    private $missionsPrincipales;

    /** @var string */
    private $connaissances;
    /** @var string */
    private $connaissancesFormation;
    /** @var string */
    private $competencesOperationnelles;
    /** @var string */
    private $competencesOperationnellesFormation;
    /** @var string */
    private $competencesComportementales;
    /** @var string */
    private $competencesComportementalesFormation;

    /** @var ArrayCollection */
    private $applications;
    /** @var string */
    private $applicationsFormation;

    /** @var ArrayCollection */
    private $activites;


    public function __construct()
    {
        $this->applications = new ArrayCollection();
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
     * @return Metier
     */
    public function getMetier()
    {
        return $this->metier;
    }

    /**
     * @param Metier $metier
     * @return FicheMetierType
     */
    public function setMetier($metier)
    {
        $this->metier = $metier;
        return $this;
    }

    /**
     * @return string
     */
    public function getMissionsPrincipales()
    {
        return $this->missionsPrincipales;
    }

    /**
     * @param string $missionsPrincipales
     * @return FicheMetierType
     */
    public function setMissionsPrincipales($missionsPrincipales)
    {
        $this->missionsPrincipales = $missionsPrincipales;
        return $this;
    }

    /**
     * @return string
     */
    public function getConnaissances()
    {
        return $this->connaissances;
    }

    /**
     * @param string $connaissances
     * @return FicheMetierType
     */
    public function setConnaissances($connaissances)
    {
        $this->connaissances = $connaissances;
        return $this;
    }

    /**
     * @return string
     */
    public function getConnaissancesFormation()
    {
        return $this->connaissancesFormation;
    }

    /**
     * @param string $connaissancesFormation
     * @return FicheMetierType
     */
    public function setConnaissancesFormation($connaissancesFormation)
    {
        $this->connaissancesFormation = $connaissancesFormation;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompetencesOperationnelles()
    {
        return $this->competencesOperationnelles;
    }

    /**
     * @param string $competencesOperationnelles
     * @return FicheMetierType
     */
    public function setCompetencesOperationnelles($competencesOperationnelles)
    {
        $this->competencesOperationnelles = $competencesOperationnelles;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompetencesOperationnellesFormation()
    {
        return $this->competencesOperationnellesFormation;
    }

    /**
     * @param string $competencesOperationnellesFormation
     * @return FicheMetierType
     */
    public function setCompetencesOperationnellesFormation($competencesOperationnellesFormation)
    {
        $this->competencesOperationnellesFormation = $competencesOperationnellesFormation;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompetencesComportementales()
    {
        return $this->competencesComportementales;
    }

    /**
     * @param string $competencesComportementales
     * @return FicheMetierType
     */
    public function setCompetencesComportementales($competencesComportementales)
    {
        $this->competencesComportementales = $competencesComportementales;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompetencesComportementalesFormation()
    {
        return $this->competencesComportementalesFormation;
    }

    /**
     * @param string $competencesComportementalesFormation
     * @return FicheMetierType
     */
    public function setCompetencesComportementalesFormation($competencesComportementalesFormation)
    {
        $this->competencesComportementalesFormation = $competencesComportementalesFormation;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param Application $application
     * @return FicheMetierType
     */
    public function addApplication($application)
    {
        $this->applications->add($application);
        return $this;
    }

    /**
     * @param Application $application
     * @return FicheMetierType
     */
    public function removeApplication($application)
    {
        $this->applications->removeElement($application);
        return $this;
    }

    /**
     * @return string
     */
    public function getApplicationsFormation()
    {
        return $this->applicationsFormation;
    }

    /**
     * @param string $formation
     * @return FicheMetierType
     */
    public function setApplicationsFormation($formation)
    {
        $this->applicationsFormation = $formation;
        return $this;
    }

    /**
     * @return FicheMetierTypeActivite[]
     */
    public function getActivites()
    {
        return $this->activites->toArray();
    }

    /**
     * @param FicheMetierTypeActivite $activite
     * @return FicheMetierType
     */
    public function addActivite($activite)
    {
        $this->activites->add($activite);
        return $this;
    }

    /**
     * @param FicheMetierTypeActivite $activite
     * @return FicheMetierType
     */
    public function removeActivite($activite)
    {
        $this->activites->removeElement($activite);
        return $this;
    }
}