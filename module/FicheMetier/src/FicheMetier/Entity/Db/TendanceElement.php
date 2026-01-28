<?php

namespace FicheMetier\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class TendanceElement implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?FicheMetier $ficheMetier = null;
    private ?TendanceType $type = null;
    private ?string $texte = null;

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

    public function getType(): ?TendanceType
    {
        return $this->type;
    }

    public function setType(?TendanceType $type): void
    {
        $this->type = $type;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): void
    {
        $this->texte = $texte;
    }

}