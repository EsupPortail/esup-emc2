<?php

namespace FicheMetier\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class MissionElement implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    const MAX_POSITION = 9999;

    private ?int $id = null;
    private ?Mission $mission = null;
    private ?string $description = null;
    private int $position = self::MAX_POSITION;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): void
    {
        $this->mission = $mission;
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

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }


}