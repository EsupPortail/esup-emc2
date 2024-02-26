<?php

namespace MissionSpecifique\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class MissionSpecifiqueType implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private Collection $missions;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle) : MissionSpecifiqueType
    {
        $this->libelle = $libelle;
        return $this;
    }

    /* @return MissionSpecifique[] */
    public function getMissions() : array
    {
        return $this->missions->toArray();
    }

    public function addMissionSpecifique(MissionSpecifique $mission) : MissionSpecifiqueType
    {
        $this->missions->add($mission);
        return $this;
    }

    public function removeMissionSpecifique(MissionSpecifique $mission) : MissionSpecifiqueType
    {
        $this->missions->removeElement($mission);
        return $this;
    }

    public function hasMissionSpecifique(MissionSpecifique $mission) : bool
    {
        return $this->missions->contains($mission);
    }
}