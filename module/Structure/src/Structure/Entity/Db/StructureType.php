<?php

namespace Structure\Entity\Db;


use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

class StructureType implements IsSynchronisableInterface
{
    use IsSynchronisableTrait;

    private ?int $id = -1;
    private ?string $code = null;
    private ?string $libelle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function __toString(): string
    {
        return ($this->getLibelle()) ?: "Aucun type";
    }

}