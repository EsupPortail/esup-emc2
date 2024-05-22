<?php

namespace Structure\Entity\Db;

use UnicaenUtilisateur\Entity\Db\AbstractUser;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Observateur implements  HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?Structure $structure = null;
    private ?AbstractUser $utilisateur = null;
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): void
    {
        $this->structure = $structure;
    }

    public function getUtilisateur(): ?AbstractUser
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?AbstractUser $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
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