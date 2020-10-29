<?php

namespace UnicaenEtat\Entity\Db;

trait HasEtatTrait {

    private $etat;

    /**
     * @return Etat|null
     */
    public function getEtat() : ?Etat
    {
        return $this->etat;
    }

    /**
     * @param Etat|null $etat
     * @return HasEtatTrait
     */
    public function setEtat(?Etat $etat)
    {
        $this->etat = $etat;
        return $this;
    }


}