<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class FicheMetier
{
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;
    /** @var Affectation */
    private $affectation;
    /** @var string */
    private $environnement;

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
     * @return FicheMetier
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Affectation
     */
    public function getAffectation()
    {
        return $this->affectation;
    }

    /**
     * @param Affectation $affectation
     * @return FicheMetier
     */
    public function setAffectation($affectation)
    {
        $this->affectation = $affectation;
        return $this;
    }



    /**
     * @return string
     */
    public function getEnvironnement()
    {
        return $this->environnement;
    }

    /**
     * @param string $environnement
     * @return FicheMetier
     */
    public function setEnvironnement($environnement)
    {
        $this->environnement = $environnement;
        return $this;
    }



}