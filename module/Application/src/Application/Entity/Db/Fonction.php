<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;

class Fonction {
   use ImportableAwareTrait;

   /** @var string */
   private $id;
   /** @var Fonction */
   private $parent;
    /** @var string */
   private $code;
    /** @var string */
   private $niveau;

   /** @var ArrayCollection */
   private $libelles;

   public function __construct()
   {
       $this->libelles = new ArrayCollection();
   }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Fonction
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Fonction
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Fonction $parent
     * @return Fonction
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
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
     * @return Fonction
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @param string $niveau
     * @return Fonction
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
        return $this;
    }

    /**
     * @return FonctionLibelle[]
     */
    public function getLibelles()
    {
        return $this->libelles->toArray();
    }

    /**
     * @param FonctionLibelle $libelle
     * @return Fonction
     */

    public function addLibelle($libelle)
    {
        $this->libelles->add($libelle);
        return $this;
    }

    /**
     * @param FonctionLibelle $libelle
     * @return Fonction
     */

    public function removeLibelle($libelle)
    {
        $this->libelles->removeElement($libelle);
        return $this;
    }

}