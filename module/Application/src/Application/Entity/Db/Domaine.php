<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class Domaine {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;

    /** @var ArrayCollection */
    private $metiers;
//    /** @var ArrayCollection (MetierFamille) */
//    private $familles;

    public function __construct()
    {
        $this->metiers = new ArrayCollection();
//        $this->familles = new ArrayCollection();
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
     * @return Metier[]
     */
    public function getMetiers()
    {
        return $this->metiers->toArray();
    }

    /**
     * @param Metier $metier
     * @return Domaine
     */
    public function addMetier($metier)
    {
        $this->metiers->add($metier);
        return $this;
    }

    /**
     * @param Metier $metier
     * @return Domaine
     */
    public function removeMetier($metier)
    {
        $this->metiers->removeElement($metier);
        return $this;
    }


//    /**
//     * @return MetierFamille[]
//     */
//    public function getFamilles()
//    {
//        return $this->familles->toArray();
//    }
//
//    /**
//     * @param MetierFamille $famille
//     * @return Domaine
//     */
//    public function addFamille($famille)
//    {
//        $this->familles->add($famille);
//        return $this;
//    }
//
//    /**
//     * @param MetierFamille $famille
//     * @return Domaine
//     */
//    public function removeFamille($famille)
//    {
//        $this->familles->removeElement($famille);
//        return $this;
//    }
}