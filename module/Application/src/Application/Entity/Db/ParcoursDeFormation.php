<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class ParcoursDeFormation {

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var ArrayCollection (string) */
    private $categories;
    /** @var ArrayCollection (Formation) */
    private $formations;

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
     * @return ParcoursDeFormation
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getCategories()
    {
        $categories = $this->categories->toArray();
        return $categories;
    }

    /**
     * @param string $categorie
     * @return boolean
     */
    public function hasCategorie($categorie)
    {
        return $this->categories->contains($categorie);
    }

    /**
     * @param string $categorie
     * @return ParcoursDeFormation
     */
    public function addCategorie($categorie)
    {
        if (! $this->categories->contains($categorie)) $this->categories->add($categorie);
        return $this;
    }

    /**
     * @param string $categorie
     * @return ParcoursDeFormation
     */
    public function removeCategorie($categorie)
    {
        $this->categories->removeElement($categorie);
        return $this;
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
     * @return boolean
     */
    public function hasFormation(Formation $formation)
    {
        return $this->formations->contains($formation);
    }

    /**
     * @param Formation $formation
     * @return ParcoursDeFormation
     */
    public function addFormation(Formation $formation)
    {
        if (! $this->formations->contains($formation)) $this->formations->add($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return ParcoursDeFormation
     */
    public function removeFormation(Formation $formation)
    {
        $this->formations->removeElement($formation);
        return $this;
    }
}