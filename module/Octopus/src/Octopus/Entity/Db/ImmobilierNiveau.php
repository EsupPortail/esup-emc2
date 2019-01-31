<?php

namespace Octopus\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class ImmobilierNiveau {
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
    private $clefSequoia;
    /** @var integer */
    private $clefGestimmo;
    /** @var ImmobilierBatiment */
    private $batiment;

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
     * @return ImmobilierBatiment
     */
    public function getBatiment()
    {
        return $this->batiment;
    }



}