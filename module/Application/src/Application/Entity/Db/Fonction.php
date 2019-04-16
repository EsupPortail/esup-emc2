<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Fonction {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $source;
    /** @var string */
    private $idSource;
    /** @var ArrayCollection */
    private $libelles;

    public function __construct()
    {
        $this->libelles = new ArrayCollection();
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
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return Fonction
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
     * @return Fonction
     */
    public function setIdSource($idSource)
    {
        $this->idSource = $idSource;
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

    public function __toString()
    {
        $tab = [];
        foreach ($this->getLibelles() as $libelle) $tab[] = $libelle->getLibelle();
        return implode("/", $tab);
    }

    /**
     * @param string $genre
     * @return FonctionLibelle
     */
    public function getDefault($genre)
    {
        /** @var FonctionLibelle $libelle */
        foreach ($this->libelles as $libelle) {
            if ($libelle->getDefault() && $libelle->getGenre() === $genre) return $libelle;
        }
        return null;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

}