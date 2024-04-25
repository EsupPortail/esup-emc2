<?php

namespace EntretienProfessionnel\Entity\Db;

use UnicaenUtilisateur\Entity\Db\AbstractUser;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Observateur implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?EntretienProfessionnel $entretien = null;
    private ?AbstractUser $user = null;
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntretienProfessionnel(): ?EntretienProfessionnel
    {
        return $this->entretien;
    }

    public function setEntretienProfessionnel(?EntretienProfessionnel $entretien): void
    {
        $this->entretien = $entretien;
    }

    public function getUser(): ?AbstractUser
    {
        return $this->user;
    }

    public function setUser(?AbstractUser $user): void
    {
        $this->user = $user;
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