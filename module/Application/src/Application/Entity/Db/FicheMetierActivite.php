<?php

namespace Application\Entity\Db;

class FicheMetierActivite {

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
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return FicheMetier
     */
    public function getFiche() : ?FicheMetier
    {
        return $this->fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetierActivite
     */
    public function setFiche(FicheMetier $fiche) : FicheMetierActivite
    {
        $this->fiche = $fiche;
        return $this;
    }

    /**
     * @return Activite
     */
    public function getActivite() : ?Activite
    {
        return $this->activite;
    }

    /**
     * @param Activite $activite
     * @return FicheMetierActivite
     */
    public function setActivite(Activite $activite) : ?FicheMetierActivite
    {
        $this->activite = $activite;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition() : ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return FicheMetierActivite
     */
    public function setPosition(int $position) : FicheMetierActivite
    {
        $this->position = $position;
        return $this;
    }
}