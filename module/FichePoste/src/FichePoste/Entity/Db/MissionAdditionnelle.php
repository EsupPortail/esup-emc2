<?php

namespace FichePoste\Entity\Db;

use Application\Entity\Db\FichePoste;
use FicheMetier\Entity\Db\Mission;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class MissionAdditionnelle implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?FichePoste $ficheposte;
    private ?Mission  $mission;
    private ?string $retraits;
    private ?string $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFicheposte(): ?FichePoste
    {
        return $this->ficheposte;
    }

    public function setFicheposte(?FichePoste $ficheposte): void
    {
        $this->ficheposte = $ficheposte;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): void
    {
        $this->mission = $mission;
    }

    public function getRetraits(): ?string
    {
        return $this->retraits;
    }

    public function setRetraits(?string $retraits): void
    {
        $this->retraits = $retraits;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

}