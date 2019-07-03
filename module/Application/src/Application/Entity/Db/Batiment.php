<?php

namespace Application\Entity\Db;

class Batiment {
    use ImportableAwareTrait;

    /** @var string */
    private $id;
    /** @var string */
    private $nom;
    /** @var string */
    private $libelle;
    /** @var Site */
    private $site;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * @return Batiment
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
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
     * @return Batiment
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site $site
     * @return Batiment
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '<span class="badge">'.$this->getSite()->getNom().'</span> ' . $this->libelle;
    }
}