<?php

namespace Autoform\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Formulaire implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;
    /** @var string */
    private $code;

    /** @var ArrayCollection */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
     * @return Formulaire
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
     * @return Formulaire
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return Categorie[]
     */
    public function getCategories()
    {
        return $this->categories->toArray();
    }

    /**
     * @param Categorie $categorie
     * @return Formulaire
     */
    public function addCategorie($categorie)
    {
        $this->categories->add($categorie);
        return $this;
    }

    /**
     * @param Categorie $categorie
     * @return Formulaire
     */
    public function removeCategorie($categorie)
    {
        $this->categories->removeElement($categorie);
        return $this;
    }

    /**
     * @param Categorie $categorie
     * @return boolean
     */
    public function hasCategorie($categorie)
    {
        return $this->categories->contains($categorie);
    }
}