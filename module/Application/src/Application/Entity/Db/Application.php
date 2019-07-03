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

    /** @var ArrayCollection */
    private  $activites;

    public function __construct()
    {
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
}