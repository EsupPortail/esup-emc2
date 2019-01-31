<?php

namespace Octopus\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class ImmobilierBatiment {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $numero;
    /** @var string */
    private $nom;
    /** @var string */
    private $libelle;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $clefSequoia;
    /** @var integer */
    private $clefGestimmo;
    /** @var ImmobilierSite */
    private $site;

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
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * @return string
     */
    public function getClefSequoia()
    {
        return $this->clefSequoia;
    }

    /**
     * @return int
     */
    public function getClefGestimmo()
    {
        return $this->clefGestimmo;
    }

    /**
     * @return ImmobilierSite
     */
    public function getSite()
    {
        return $this->site;
    }


}