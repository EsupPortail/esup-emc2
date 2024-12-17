<?php

namespace Structure\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

class StructureGestionnaire implements HasPeriodeInterface, IsSynchronisableInterface
{
    use IsSynchronisableTrait;
    use HasPeriodeTrait;

    private ?int $id = -1;
    private ?Structure $structure = null;
    private ?Agent $agent = null;
    private ?int $fonctionId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(Structure $structure): void
    {
        $this->structure = $structure;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function getFonctionId(): ?int
    {
        return $this->fonctionId;
    }

    public function setFonctionId(?int $fonctionId): void
    {
        $this->fonctionId = $fonctionId;
    }

}