<?php

namespace Octopus\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class ImmobilierSite {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
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

}