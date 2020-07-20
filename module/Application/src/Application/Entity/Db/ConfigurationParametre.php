<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class ConfigurationParametre implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var ConfigurationCategorie */
    private $categorie;
    /** @var string */
    private $nom;
    /** @var string */
    private $valeur;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ConfigurationCategorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param ConfigurationCategorie $categorie
     * @return ConfigurationParametre
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return ConfigurationParametre
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * @param string $valeur
     * @return ConfigurationParametre
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;
        return $this;
    }

}