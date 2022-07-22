<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class ConfigurationCategorie implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $nom;
    /** @var ArrayCollection (ConfigurationParametre) */
    private $parametres;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ConfigurationCategorie
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return ConfigurationCategorie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return ConfigurationParametre[]
     */
    public function getParametres()
    {
        return $this->parametres->toArray();
    }

    /**
     * @param ConfigurationParametre[] $parametres
     * @return ConfigurationCategorie
     */
    public function setParametres($parametres)
    {
        $this->parametres = $parametres;
        return $this;
    }


}