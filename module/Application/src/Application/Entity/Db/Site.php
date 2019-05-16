<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class Site {
    use ImportableAwareTrait;

    /** @var string */
    private $id;
    /** @var string */
    private $nom;
    /** @var string */
    private $libelle;

    /** @var ArrayCollection */
    private $batiments;

    public function __construct()
    {
        $this->batiments = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Site
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
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
     * @return Site
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Batiment[]
     */
    public function getBatiments()
    {
        return $this->batiments->toArray();
    }

    /**
     * @param Batiment $batiment
     * @return Site
     */
    public function addBatiment($batiment)
    {
        $this->batiments->add($batiment);
        return $this;
    }

    /**
     * @param Batiment $batiment
     * @return Site
     */
    public function removeBatiment($batiment)
    {
        $this->batiments->removeElement($batiment);
        return $this;
    }
}