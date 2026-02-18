<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Agent;
use Structure\Entity\Db\Structure;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentForceSansObligation implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const FORCE_EXCLUS = 'FORCE_SANS_EXCLUS';
    const FORCE_SANS_OBLIGATION = 'FORCE_SANS_OBLIGATION';
    const FORCE_AVEC_OBLIGATION = 'FORCE_AVEC_OBLIGATION';
    const FORCAGE_ARRAY = [
        self::FORCE_SANS_OBLIGATION => "Forcer sans obligation d'entretien professionnel",
        self::FORCE_AVEC_OBLIGATION => "Forcer l'obligation d'entretien professionnel",
        self::FORCE_EXCLUS          => "Forcer l'exclusion de la campagne",
    ];

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?Structure $structure = null;
    private ?Campagne $campagne = null;
    private ?string $type = null;
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

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): void
    {
        $this->structure = $structure;
    }


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
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