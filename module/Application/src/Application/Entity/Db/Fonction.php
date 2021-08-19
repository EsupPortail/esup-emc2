<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Metier\Entity\Db\Domaine;

class Fonction {
   use DbImportableAwareTrait;

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

   /** @var Domaine */
   private $domaine;
   /** @var ArrayCollection (Metier) */
   private $metiers;

   public function __construct()
   {
       $this->libelles = new ArrayCollection();
       $this->metiers = new ArrayCollection();
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

    /**
     * @return Domaine
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Fonction
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;
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
     * @return Fonction
     */
    public function addMetier($metier)
    {
        $this->metiers->add($metier);
        return $this;
    }

    /**
     * @param Metier $metier
     * @return Fonction
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
        $array = [];
        foreach ($this->getLibelles() as $libelle) {
            $array[] = $libelle->getLibelle();
        }

        return implode("/", $array);
    }
}