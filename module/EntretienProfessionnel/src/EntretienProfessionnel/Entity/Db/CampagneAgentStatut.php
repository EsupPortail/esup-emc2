<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Agent;
use DateTime;
use Structure\Entity\Db\Structure;

class CampagneAgentStatut {

    const EXCLUS = 'EXCLUS';
    const OBLIGATOIRE = 'OBLIGATOIRE';
    const FACULTATIF = 'FACULTATIF';

    private ?int $id = null;
    private ?Campagne $campagne = null;
    private ?Structure $structure = null;
    private ?Agent $agent = null;
    private ?EntretienProfessionnel $entretienProfessionnel = null;
    private ?string  $statut = null;
    private ?string  $raison = null;
    private ?DateTime $refreshDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCampagne(): ?Campagne
    {
        return $this->campagne;
    }

    public function setCampagne(?Campagne $campagne): void
    {
        $this->campagne = $campagne;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): void
    {
        $this->structure = $structure;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): void
    {
        $this->statut = $statut;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): void
    {
        $this->raison = $raison;
    }

    public function getRefreshDate(): ?DateTime
    {
        return $this->refreshDate;
    }

    public function setRefreshDate(?DateTime $refreshDate): void
    {
        $this->refreshDate = $refreshDate;
    }

    public function getEntretienProfessionnel(): ?EntretienProfessionnel
    {
        return $this->entretienProfessionnel;
    }

    public function setEntretienProfessionnel(?EntretienProfessionnel $entretienProfessionnel): void
    {
        $this->entretienProfessionnel = $entretienProfessionnel;
    }
}


