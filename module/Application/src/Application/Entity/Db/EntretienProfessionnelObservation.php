<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class EntretienProfessionnelObservation implements HistoriqueAwareInterface {
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return EntretienProfessionnel
     */
    public function getEntretien()
    {
        return $this->entretien;
    }

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnelObservation
     */
    public function setEntretien($entretien)
    {
        $this->entretien = $entretien;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservationAgentEntretien()
    {
        return $this->observationAgentEntretien;
    }

    /**
     * @param string $observationAgentEntretien
     * @return EntretienProfessionnelObservation
     */
    public function setObservationAgentEntretien($observationAgentEntretien)
    {
        $this->observationAgentEntretien = $observationAgentEntretien;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservationAgentPerspective()
    {
        return $this->observationAgentPerspective;
    }

    /**
     * @param string $observationAgentPerspective
     * @return EntretienProfessionnelObservation
     */
    public function setObservationAgentPerspective($observationAgentPerspective)
    {
        $this->observationAgentPerspective = $observationAgentPerspective;
        return $this;
    }
}