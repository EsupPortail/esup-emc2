<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Metier\Entity\HasMetierInterface;
use Metier\Entity\HasMetierTrait;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentTutorat implements HistoriqueAwareInterface, HasMetierInterface, HasPeriodeInterface, HasEtatInterface {
    use HasMetierTrait;
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;
    use HasEtatTrait;

    /** @var int */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Agent|null  */
    private $cible;
    /** @var string|null */
    private $complement;
    /** @var bool|null */
    private $formation;

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
     * @return AgentTutorat
     */
    public function setAgent(Agent $agent): AgentTutorat
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Agent|null
     */
    public function getCible(): ?Agent
    {
        return $this->cible;
    }

    /**
     * @param Agent|null $cible
     * @return AgentTutorat
     */
    public function setCible(?Agent $cible): AgentTutorat
    {
        $this->cible = $cible;
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
     * @return AgentTutorat
     */
    public function setComplement(?string $complement): AgentTutorat
    {
        $this->complement = $complement;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFormation(): ?bool
    {
        return $this->formation;
    }

    /**
     * @param bool|null $formation
     * @return AgentTutorat
     */
    public function setFormation(?bool $formation): AgentTutorat
    {
        $this->formation = $formation;
        return $this;
    }

}