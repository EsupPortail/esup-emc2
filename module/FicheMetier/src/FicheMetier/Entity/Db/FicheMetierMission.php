<?php

namespace FicheMetier\Entity\Db;

class FicheMetierMission {

    private ?int $id = null;
    private ?Mission $mission = null;
    private ?FicheMetier $ficheMetier = null;
    private ?int $ordre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): void
    {
        $this->mission = $mission;
    }

    public function getFicheMetier(): ?FicheMetier
    {
        return $this->ficheMetier;
    }

    public function setFicheMetier(?FicheMetier $ficheMetier): void
    {
        $this->ficheMetier = $ficheMetier;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): void
    {
        $this->ordre = $ordre;
    }


}