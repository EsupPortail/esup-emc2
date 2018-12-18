<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class MetierFamille {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $couleur;

    /** @var ArrayCollection */
    private $metiers;

    /**
     * MetierFamille constructor.
     */
    public function __construct()
    {
        $this->metiers = new ArrayCollection();
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
     * @return MetierFamille
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * @param string $couleur
     * @return MetierFamille
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMetiers()
    {
        return $this->metiers;
    }

    /**
     * @param Metier $metier
     * @return MetierFamille
     */
    public function addMetier($metier)
    {
        $this->metiers->add($metier);
        return $this;
    }

    /**
     * @param Metier $metier
     * @return MetierFamille
     */
    public function removeMetier($metier)
    {
        $this->metiers->removeElement($metier);
        return $this;
    }

}