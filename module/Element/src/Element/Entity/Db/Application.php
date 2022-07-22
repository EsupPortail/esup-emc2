<?php

namespace Element\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Application implements HistoriqueAwareInterface, HasDescriptionInterface {
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $url;
    /** @var boolean */
    private $actif;
    /** @var ApplicationTheme */
    private $groupe;

    public function __construct()
    {
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
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Application
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Application
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActif()
    {
        return $this->actif;
    }

    /**
     * @param bool $actif
     * @return Application
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
        return $this;
    }

    /**
     * @return ApplicationTheme
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @param ApplicationTheme $groupe
     * @return Application
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;
        return $this;
    }
}