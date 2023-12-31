<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class MissionSpecifiqueTheme implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var ArrayCollection (MissionSpecifique) */
    private $missions;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return MissionSpecifiqueTheme
     */
    public function setLibelle(?string  $libelle) : MissionSpecifiqueTheme
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return MissionSpecifique[]
     */
    public function getMissions() : array
    {
        return $this->missions->toArray();
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifiqueTheme
     */
    public function addMissionSpecifique(MissionSpecifique $mission): MissionSpecifiqueTheme
    {
        $this->missions->add($mission);
        return $this;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifiqueTheme
     */
    public function removeMissionSpecifique(MissionSpecifique $mission): MissionSpecifiqueTheme
    {
        $this->missions->removeElement($mission);
        return $this;
    }

    /**
     * @param MissionSpecifique $mission
     * @return boolean
     */
    public function hasMissionSpecifique(MissionSpecifique $mission):bool
    {
        return $this->missions->contains($mission);
    }
}