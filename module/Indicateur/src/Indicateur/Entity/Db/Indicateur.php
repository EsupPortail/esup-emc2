<?php

namespace Indicateur\Entity\Db;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class Indicateur {

    const ENTITY_COMPOSANTE='Composante';
    const ENTITY_ETUDIANT='Etudiant';

    /** @var integer */
    private $id;
    /** @var string */
    private $titre;
    /** @var string */
    private $description;
    /** @var string */
    private $requete;
    /** @var DateTime */
    private $dernierRafraichissement;
    /** @var string */
    private $viewId;
    /** @var string */
    private $entity;

    /** @var ArrayCollection (Abonnement) */
    private $abonnements;

    public function __construct()
    {
        $this->abonnements = new ArrayCollection();
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
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     * @return Indicateur
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
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
     * @return Indicateur
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequete()
    {
        return $this->requete;
    }

    /**
     * @param string $requete
     * @return Indicateur
     */
    public function setRequete($requete)
    {
        $this->requete = $requete;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDernierRafraichissement()
    {
        return $this->dernierRafraichissement;
    }

    /**
     * @param DateTime $dernierRafraichissement
     * @return Indicateur
     */
    public function setDernierRafraichissement($dernierRafraichissement)
    {
        $this->dernierRafraichissement = $dernierRafraichissement;
        return $this;
    }

    /**
     * @return string
     */
    public function getViewId()
    {
        return $this->viewId;
    }

    /**
     * @param string $viewId
     * @return Indicateur
     */
    public function setViewId($viewId)
    {
        $this->viewId = $viewId;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     * @return Indicateur
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return Abonnement[]
     */
    public function getAbonnements()
    {
        return $this->abonnements->toArray();
    }

    /**
     * @param Abonnement $abonnement
     * @return Indicateur
     */
    public function addAbonnement($abonnement)
    {
        $this->abonnements->add($abonnement);
        return $this;
    }

    /**
     * @param Abonnement $abonnement
     * @return Indicateur
     */
    public function removeAbonnement($abonnement)
    {
        $this->abonnements->removeElement($abonnement);
        return $this;
    }

    /**
     * @param Abonnement $abonnement
     * @return Indicateur
     */
    public function hasAbonnement($abonnement)
    {
        $this->abonnements->contains($abonnement);
        return $this;
    }
}