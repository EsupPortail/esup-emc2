<?php

namespace Agent\Entity\Db;

use Application\Entity\Db\Agent;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

class AgentRef implements IsSynchronisableInterface
{
    use IsSynchronisableTrait;

    private ?string $id = null;
    private ?Agent $agent = null;
    private ?string $source = null;
    private ?string $idSource = null;

    public function getId(): ?string
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

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    public function getIdSource(): ?string
    {
        return $this->idSource;
    }

    public function setIdSource(?string $idSource): void
    {
        $this->idSource = $idSource;
    }

}