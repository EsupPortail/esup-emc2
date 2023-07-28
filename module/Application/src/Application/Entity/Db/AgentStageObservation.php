<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Metier\Entity\HasMetierInterface;
use Metier\Entity\HasMetierTrait;
use Structure\Entity\Db\Structure;
use UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentStageObservation implements HistoriqueAwareInterface, HasMetierInterface, HasPeriodeInterface {
    use HasMetierTrait;
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Structure|null */
    private $structure;
    /** @var string|null */
    private $complement;
    /** @var UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat|null */
    private $etat;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Agent
     */
    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return AgentStageObservation
     */
    public function setAgent(Agent $agent): AgentStageObservation
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Structure|null
     */
    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    /**
     * @param Structure|null $structure
     * @return AgentStageObservation
     */
    public function setStructure(?Structure $structure): AgentStageObservation
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplement(): ?string
    {
        return $this->complement;
    }

    /**
     * @param string|null $complement
     * @return AgentStageObservation
     */
    public function setComplement(?string $complement): AgentStageObservation
    {
        $this->complement = $complement;
        return $this;
    }

    /**
     * @return UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat|null
     */
    public function getEtat(): ?UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat
    {
        return $this->etat;
    }

    /**
     * @param UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat|null $etat
     * @return AgentStageObservation
     */
    public function setEtat(?UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat $etat): AgentStageObservation
    {
        $this->etat = $etat;
        return $this;
    }

}