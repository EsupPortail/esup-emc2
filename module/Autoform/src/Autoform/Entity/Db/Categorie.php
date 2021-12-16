<?php

namespace Autoform\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Categorie {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var integer */
    private $ordre;
    /** @var Formulaire */
    private $formulaire;

    /** @var ArrayCollection */
    private $champs;

    public function __construct()
    {
        $this->champs = new ArrayCollection();
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Categorie
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return Categorie
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     * @return Categorie
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
        return $this;
    }

    /**
     * @return Formulaire
     */
    public function getFormulaire()
    {
        return $this->formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return Categorie
     */
    public function setFormulaire($formulaire)
    {
        $this->formulaire = $formulaire;
        return $this;
    }

    /**
     * @return Champ[]
     */
    public function getChamps()
    {
        return $this->champs->toArray();
    }

    /**
     * @param Champ $champ
     * @return Categorie
     */
    public function addChamp($champ)
    {
        $this->champs->add($champ);
        return $this;
    }

    /**
     * @param Champ $champ
     * @return Categorie
     */
    public function removeChamp($champ)
    {
        $this->champs->removeElement($champ);
        return $this;
    }

    /**
     * @param Champ $champ
     * @return boolean
     */
    public function hasChamp($champ)
    {
        return $this->champs->contains($champ);
    }

}