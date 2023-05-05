<?php

namespace EntretienProfessionnel\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Observation implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?EntretienProfessionnel $entretien = null;
    private ?string $observationAgentEntretien = null;
    private ?string $observationAgentPerspective = null;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getEntretien() : ?EntretienProfessionnel
    {
        return $this->entretien;
    }

    public function setEntretien(EntretienProfessionnel $entretien) : void
    {
        $this->entretien = $entretien;
    }

    public function getObservationAgentEntretien() : ?string
    {
        return $this->observationAgentEntretien;
    }

    public function setObservationAgentEntretien(?string $observationAgentEntretien) : void
    {
        $this->observationAgentEntretien = $observationAgentEntretien;
    }

    public function getObservationAgentPerspective() : ?string
    {
        return $this->observationAgentPerspective;
    }

    public function setObservationAgentPerspective(?string $observationAgentPerspective) : void
    {
        $this->observationAgentPerspective = $observationAgentPerspective;
    }
}