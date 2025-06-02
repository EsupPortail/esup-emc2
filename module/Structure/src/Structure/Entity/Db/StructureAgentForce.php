<?php

namespace Structure\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\HasAgentInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class StructureAgentForce implements HistoriqueAwareInterface, HasAgentInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?Structure $structure = null;
    private ?Agent $agent = null;

    public function getId() : int
    {
        return $this->id;
    }

    public function getStructure() : ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure) : void
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

    public function getDenomination() : string {
        return $this->getAgent()->getDenomination();
    }

}