<?php

namespace Element\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Niveau implements HistoriqueAwareInterface, HasDescriptionInterface {
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    private ?int $id = null;
    private ?string $type = null;
    private ?string $libelle = null;
    private ?int $niveau = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(?int $niveau): void
    {
        $this->niveau = $niveau;
    }

}