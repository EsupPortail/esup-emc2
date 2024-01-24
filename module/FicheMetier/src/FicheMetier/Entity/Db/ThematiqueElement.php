<?php

namespace FicheMetier\Entity\Db;

use Element\Entity\Db\Interfaces\HasNiveauInterface;
use Element\Entity\Db\Traits\HasNiveauTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class ThematiqueElement implements HistoriqueAwareInterface, HasNiveauInterface {
    use HistoriqueAwareTrait;
    use HasNiveauTrait;

    private ?int $id = null;
    private ?FicheMetier $ficheMetier = null;
    private ?ThematiqueType $type = null;
    private ?string $complement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFicheMetier(): ?FicheMetier
    {
        return $this->ficheMetier;
    }

    public function setFicheMetier(?FicheMetier $ficheMetier): void
    {
        $this->ficheMetier = $ficheMetier;
    }

    public function getType(): ?ThematiqueType
    {
        return $this->type;
    }

    public function setType(?ThematiqueType $type): void
    {
        $this->type = $type;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): void
    {
        $this->complement = $complement;
    }

}