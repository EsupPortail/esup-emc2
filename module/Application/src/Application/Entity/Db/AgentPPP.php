<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentPPP implements HasPeriodeInterface, HistoriqueAwareInterface {
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var string|null */
    private $type;
    /** @var string|null */
    private $libelle;
    /** @var Etat|null */
    private $etat;
    /** @var float|null */
    private $formationCPF;
    /** @var float|null */
    private $formationCout;
    /** @var float|null */
    private $formationPriseEnCharge;
    /** @var string|null */
    private $formationOrganisme;
    /** @var string|null */
    private $complement;

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
    public function getAgent(): Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return AgentPPP
     */
    public function setAgent(Agent $agent): AgentPPP
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return AgentPPP
     */
    public function setType(?string $type): AgentPPP
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return AgentPPP
     */
    public function setLibelle(?string $libelle): AgentPPP
    {
        $this->libelle = $libelle;
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
     * @return AgentPPP
     */
    public function setEtat(?Etat $etat): AgentPPP
    {
        $this->etat = $etat;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFormationCPF(): ?float
    {
        return $this->formationCPF;
    }

    /**
     * @param float|null $formationCPF
     * @return AgentPPP
     */
    public function setFormationCPF(?float $formationCPF): AgentPPP
    {
        $this->formationCPF = $formationCPF;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFormationCout(): ?float
    {
        return $this->formationCout;
    }

    /**
     * @param float|null $formationCout
     * @return AgentPPP
     */
    public function setFormationCout(?float $formationCout): AgentPPP
    {
        $this->formationCout = $formationCout;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFormationPriseEnCharge(): ?float
    {
        return $this->formationPriseEnCharge;
    }

    /**
     * @param float|null $formationPriseEnCharge
     * @return AgentPPP
     */
    public function setFormationPriseEnCharge(?float $formationPriseEnCharge): AgentPPP
    {
        $this->formationPriseEnCharge = $formationPriseEnCharge;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormationOrganisme(): ?string
    {
        return $this->formationOrganisme;
    }

    /**
     * @param string|null $formationOrganisme
     * @return AgentPPP
     */
    public function setFormationOrganisme(?string $formationOrganisme): AgentPPP
    {
        $this->formationOrganisme = $formationOrganisme;
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
     * @return AgentPPP
     */
    public function setComplement(?string $complement): AgentPPP
    {
        $this->complement = $complement;
        return $this;
    }
}