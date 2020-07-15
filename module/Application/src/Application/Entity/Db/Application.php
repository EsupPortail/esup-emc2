<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class Application {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;
    /** @var string */
    private $url;
    /** @var boolean */
    private $actif;
    /** @var ApplicationGroupe */
    private $groupe;

    /** @var ArrayCollection */
    private  $activites;
    /** @var ArrayCollection */
    private  $formations;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
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
     * @return Application
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
     * @return Application
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Application
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActif()
    {
        return $this->actif;
    }

    /**
     * @param bool $actif
     * @return Application
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
        return $this;
    }

    /**
     * @return ApplicationGroupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @param ApplicationGroupe $groupe
     * @return Application
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;
        return $this;
    }

    /**
     * @return Activite[]
     */
    public function getActivites()
    {
        return $this->activites->toArray();
    }

    /**
     * @param Activite $activite
     * @return Application
     */
    public function addApplication($activite)
    {
        $this->activites->add($activite);
        return $this;
    }

    /**
     * @param Activite $activite
     * @return Application
     */
    public function removeApplication($activite)
    {
        $this->activites->removeElement($activite);
        return $this;
    }

    /**
     * @param Activite $activite
     * @return boolean
     */
    public function hasApplication($activite)
    {
        return $this->activites->contains($activite);
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
     * @return Application
     */
    public function addFormation($formation)
    {
        $this->formations->add($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return Application
     */
    public function removeFormation($formation)
    {
        $this->formations->removeElement($formation);
        return $this;
    }
}