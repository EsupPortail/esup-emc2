<?php

namespace Metier\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Domaine implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $typeFonction;
    /** @var FamilleProfessionnelle */
    private $famille;
    /** @var ArrayCollection */
    private $metiers;

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
     * @return Domaine
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeFonction()
    {
        return $this->typeFonction;
    }

    /**
     * @param string $typeFonction
     * @return Domaine
     */
    public function setTypeFonction($typeFonction)
    {
        $this->typeFonction = $typeFonction;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
}