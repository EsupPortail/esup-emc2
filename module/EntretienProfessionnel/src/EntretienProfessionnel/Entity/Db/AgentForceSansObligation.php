<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Agent;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentForceSansObligation implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?Campagne $campagne = null;
    private ?string $raison = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function getCampagne(): ?Campagne
    {
        return $this->campagne;
    }

    public function setCampagne(?Campagne $campagne): void
    {
        $this->campagne = $campagne;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): void
    {
        $this->raison = $raison;
    }
}