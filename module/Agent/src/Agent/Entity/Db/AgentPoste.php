<?php

namespace Agent\Entity\Db;

use Application\Entity\Db\Agent;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

class AgentPoste implements IsSynchronisableInterface
{
    use IsSynchronisableTrait;

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?string $code = null;
    private ?string $libelle = null;
    private ?string $codeFonction = null;
    private ?string $codeEmploiType = null;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getCodeFonction(): ?string
    {
        return $this->codeFonction;
    }

    public function setCodeFonction(?string $codeFonction): void
    {
        $this->codeFonction = $codeFonction;
    }

    public function getCodeEmploiType(): ?string
    {
        return $this->codeEmploiType;
    }

    public function setCodeEmploiType(?string $codeEmploiType): void
    {
        $this->codeEmploiType = $codeEmploiType;
    }

}
