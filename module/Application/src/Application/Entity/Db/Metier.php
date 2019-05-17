<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class Metier {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;

    /** @var Fonction */
    private $fonction;


    /** @var ArrayCollection (FicheMetierType) */
    private $fichesMetiers;

    public function __construct()
    {
        $this->fichesMetiers = new ArrayCollection();
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
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return Fonction
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * @param Fonction $fonction
     * @return Metier
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;
        return $this;
    }



    public function __toString()
    {
        return $this->getLibelle();
    }

    /**
     * @return ArrayCollection
     */
    public function getFichesMetiers()
    {
        return $this->fichesMetiers;
    }

}