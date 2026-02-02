<?php

namespace Carriere\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FamilleProfessionnelle implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?Correspondance $correspondance = null;
    private ?int $position = null;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle) : void
    {
        $this->libelle = $libelle;
    }

    public function getCorrespondance(): ?Correspondance
    {
        return $this->correspondance;
    }

    public function setCorrespondance(?Correspondance $correspondance): void
    {
        $this->correspondance = $correspondance;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function __toString() : string
    {
        return $this->getLibelle();
    }
    public function computeCode(): ?string
    {
        $code  = $this->getCorrespondance()?->getCategorie() ?? "?";
        $code .= $this->getPosition() ?? "?";
        return $code;
    }
}