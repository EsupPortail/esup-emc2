<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class FonctionLibelle {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Fonction */
    private $fonction;
    /** @var string */
    private $libelle;
    /** @var string */
    private $genre;
    /** @var string */
    private $default;

    /** @var string */
    private $source;
    /** @var string */
    private $idSource;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return FonctionLibelle
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return boolean
     */
    public function getDefault()
    {
        return ($this->default === 'O');
    }

    /**
     * @param string $default
     * @return FonctionLibelle
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return FonctionLibelle
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdSource()
    {
        return $this->idSource;
    }

    /**
     * @param string $idSource
     * @return FonctionLibelle
     */
    public function setIdSource($idSource)
    {
        $this->idSource = $idSource;
        return $this;
    }



}