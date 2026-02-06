<?php

namespace EntretienProfessionnel\Entity\Db;

use UnicaenAutoform\Entity\Db\Champ;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class CampagneConfigurationRecopie implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $formulaire = null;
    private ?Champ $from = null;
    private ?Champ $to = null;
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFormulaire(): ?string
    {
        return $this->formulaire;
    }

    public function setFormulaire(?string $formulaire): void
    {
        $this->formulaire = $formulaire;
    }

    public function getFrom(): ?Champ
    {
        return $this->from;
    }

    public function setFrom(?Champ $from): void
    {
        $this->from = $from;
    }

    public function getTo(): ?Champ
    {
        return $this->to;
    }

    public function setTo(?Champ $to): void
    {
        $this->to = $to;
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
