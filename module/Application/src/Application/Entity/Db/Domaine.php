<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class Domaine {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;

    /** @var FamilleProfessionnelle */
    private $famille;
    /** @var ArrayCollection */
    private $fonctions;

    public function __construct()
    {
        $this->fonctions = new ArrayCollection();
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
     * @return Domaine
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return FamilleProfessionnelle
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return Domaine
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;
        return $this;
    }

    /**
     * @return Fonction[]
     */
    public function getFonctions()
    {
        return $this->fonctions->toArray();
    }

    /**
     * @param Fonction $fonction
     * @return Domaine
     */
    public function addFonction($fonction)
    {
        $this->fonctions->add($fonction);
        return $this;
    }

    /**
     * @param Fonction $fonction
     * @return Domaine
     */
    public function removeFonction($fonction)
    {
        $this->fonctions->removeElement($fonction);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}