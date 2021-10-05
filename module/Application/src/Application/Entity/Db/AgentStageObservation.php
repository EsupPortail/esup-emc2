<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Metier\Entity\Db\Metier;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class AgentStageObservation implements HistoriqueAwareInterface, HasPeriodeInterface {
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Structure|null */
    private $structure;
    /** @var Metier|null */
    private $metier;
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
     * @return Metier|null
     */
    public function getMetier(): ?Metier
    {
        return $this->metier;
    }

    /**
     * @param Metier|null $metier
     * @return AgentStageObservation
     */
    public function setMetier(?Metier $metier): AgentStageObservation
    {
        $this->metier = $metier;
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