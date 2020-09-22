<?php

namespace Autoform\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Champ {
    use HistoriqueAwareTrait;

    const TYPE_SPACER       = "Spacer";
    const TYPE_LABEL        = "Label";
    const TYPE_TEXT         = "Text";
    const TYPE_TEXTAREA     = "Textarea";
    const TYPE_CHECKBOX     = "Checkbox";
    const TYPE_SELECT       = "Select";
    const TYPE_PERIODE      = "Periode";
    const TYPE_FORMATION    = "Formation";
    const TYPE_ANNEE        = "Annee";
    const TYPE_NOMBRE       = "Number";
    const TYPE_MULTIPLE     = "Multiple";
    const TYPE_ENTITY       = "Entity";
    const TYPE_ENTITY_MULTI = "Entity Multiple";


    /** @var integer */
    private $id;
    /** @var Categorie */
    private $categorie;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var string */
    private $texte;
    /** @var integer */
    private $ordre;
    /** @var string */
    private $element;
    /** @var string */
    private $balise;
    /** @var string */
    private $options;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Champ
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
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
     * @return Champ
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
     * @return Champ
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * @param string $texte
     * @return Champ
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
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
     * @return Champ
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
        return $this;
    }

    /**
     * @return string
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @param string $element
     * @return Champ
     */
    public function setElement($element)
    {
        $this->element = $element;
        return $this;
    }

    /**
     * @return string
     */
    public function getBalise()
    {
        return $this->balise;
    }

    /**
     * @param string $balise
     * @return Champ
     */
    public function setBalise($balise)
    {
        $this->balise = $balise;
        return $this;
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $options
     * @return Champ
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

}