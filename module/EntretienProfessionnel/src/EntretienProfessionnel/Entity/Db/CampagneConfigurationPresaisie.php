<?php

namespace EntretienProfessionnel\Entity\Db;

use UnicaenAutoform\Entity\Db\Champ;
use UnicaenAutoform\Entity\Db\Formulaire;
use UnicaenRenderer\Entity\Db\Macro;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class CampagneConfigurationPresaisie implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?Formulaire $formulaire = null;
    private ?Champ $champ = null;
    private ?Macro $macro = null;
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFormulaire(): ?Formulaire
    {
        return $this->formulaire;
    }

    public function setFormulaire(?Formulaire $formulaire): void
    {
        $this->formulaire = $formulaire;
    }

    public function getChamp(): ?Champ
    {
        return $this->champ;
    }

    public function setChamp(?Champ $champ): void
    {
        $this->champ = $champ;
    }

    public function getMacro(): ?Macro
    {
        return $this->macro;
    }

    public function setMacro(?Macro $macro): void
    {
        $this->macro = $macro;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


}
