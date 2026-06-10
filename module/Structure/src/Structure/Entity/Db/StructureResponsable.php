<?php

namespace Structure\Entity\Db;

use Agent\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use DateTime;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use RuntimeException;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class StructureResponsable implements HasPeriodeInterface, IsSynchronisableInterface, HistoriqueAwareInterface, ResourceInterface
{
    use IsSynchronisableTrait;
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;

    public function getResourceId(): string
    {
        return "Responsabilité";
    }

    private ?string $id = null;
    private ?Structure $structure = null;
    private ?Agent $agent = null;
    private ?int $fonctionId = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function generateId(): ?string
    {
        if ($this->getAgent() === null) { throw new RuntimeException("StructureResponsable::generateId() : Agent manquant");}
        if ($this->getStructure() === null) { throw new RuntimeException("StructureResponsable::generateId() : Structure manquante");}
        if ($this->getDateDebut() === null) { throw new RuntimeException("StructureResponsable::generateId() : Date de début manquant");}
        if ($this->sourceId === null) $this->sourceId = 'EMC2';
        $id = $this->sourceId . "-". $this->getStructure()->getId() . "-" . $this->getAgent()->getId() . "-". $this->getDateDebut()->format('dmYHi') . "-". (new DateTime())->format('YmdHis');
        return $id;
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