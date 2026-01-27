<?php

namespace FicheMetier\Entity\Db;


use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class ActiviteElement implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const MAX_POSITION = 9999;

    private ?int $id = null;
    private ?Activite $activite = null;
    private ?string $description = null;
    private int $position = self::MAX_POSITION;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivite(): ?Activite
    {
        return $this->activite;
    }

    public function setActivite(?Activite $activite): void
    {
        $this->activite = $activite;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position = 9999): void
    {
        $this->position = $position;
    }


}
