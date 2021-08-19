<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\DbImportableAwareTrait;

class FonctionLibelle {
    use DbImportableAwareTrait;

    /** @var string */
    private $id;
    /** @var Fonction */
    private $fonction;
    /** @var string */
    private $libelle;
    /** @var string */
    private $genre;
    /** @var string */
    private $defaut;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * @return FonctionLibelle
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;
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
     * @return FonctionLibelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param string $genre
     * @return FonctionLibelle
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaut()
    {
        return $this->defaut;
    }

    /**
     * @param string $defaut
     * @return FonctionLibelle
     */
    public function setDefaut($defaut)
    {
        $this->defaut = $defaut;
        return $this;
    }


}