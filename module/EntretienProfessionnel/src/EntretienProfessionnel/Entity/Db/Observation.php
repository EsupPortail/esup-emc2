<?php

namespace EntretienProfessionnel\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Observation implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var EntretienProfessionnel */
    private $entretien;
    /** @var string */
    private $observationAgentEntretien;
    /** @var string */
    private $observationAgentPerspective;

    /**
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return EntretienProfessionnel
     */
    public function getEntretien() : ?EntretienProfessionnel
    {
        return $this->entretien;
    }

    /**
     * @param EntretienProfessionnel $entretien
     * @return Observation
     */
    public function setEntretien(EntretienProfessionnel $entretien) : Observation
    {
        $this->entretien = $entretien;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getObservationAgentEntretien() : ?string
    {
        return $this->observationAgentEntretien;
    }

    /**
     * @param string|null $observationAgentEntretien
     * @return Observation
     */
    public function setObservationAgentEntretien(?string $observationAgentEntretien) : Observation
    {
        $this->observationAgentEntretien = $observationAgentEntretien;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservationAgentPerspective() : ?string
    {
        return $this->observationAgentPerspective;
    }

    /**
     * @param string|null $observationAgentPerspective
     * @return Observation
     */
    public function setObservationAgentPerspective(?string $observationAgentPerspective) : Observation
    {
        $this->observationAgentPerspective = $observationAgentPerspective;
        return $this;
    }
}