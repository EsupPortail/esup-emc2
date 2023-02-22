<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Metier\Entity\HasMetierInterface;
use Metier\Entity\HasMetierTrait;
use Structure\Entity\Db\Structure;
use UnicaenEtat\Entity\Db\Etat;
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
    /** @var Etat|null */
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
     * @return Etat|null
     */
    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    /**
     * @param Etat|null $etat
     * @return AgentStageObservation
     */
    public function setEtat(?Etat $etat): AgentStageObservation
    {
        $this->etat = $etat;
        return $this;
    }

}