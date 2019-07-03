<?php

namespace Application\Entity\Db;

class FicheMetierTypeActivite {

    /** @var int */
    private $id;
    /** @var FicheMetier */
    private $fiche;
    /** @var Activite */
    private $activite;
    /** @var int */
    private $position;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FicheMetier
     */
    public function getFiche()
    {
        return $this->fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetierTypeActivite
     */
    public function setFiche($fiche)
    {
        $this->fiche = $fiche;
        return $this;
    }

    /**
     * @return Activite
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * @param Activite $activite
     * @return FicheMetierTypeActivite
     */
    public function setActivite($activite)
    {
        $this->activite = $activite;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return FicheMetierTypeActivite
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
}