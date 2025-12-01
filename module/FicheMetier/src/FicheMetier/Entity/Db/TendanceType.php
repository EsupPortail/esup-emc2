<?php

namespace FicheMetier\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class TendanceType implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const IMPACT = 'impact';
    const FACTEUR = 'facteur';

    private ?int $id = null;
    private ?string $code = null;
    private ?string $libelle = null;
    private ?string $description = null;
    private ?int $ordre = null;
    private bool $obligatoire = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function isObligatoire(): bool
    {
        return $this->obligatoire;
    }

    public function setObligatoire(bool $obligatoire): void
    {
        $this->obligatoire = $obligatoire;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): void
    {
        $this->ordre = $ordre;
    }

}