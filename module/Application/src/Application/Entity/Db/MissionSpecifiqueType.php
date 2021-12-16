<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class MissionSpecifiqueType implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const TYPE_ID_REFERENT = 1;
    const TYPE_ID_CHARGE   = 2;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return MissionSpecifiqueType
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return MissionSpecifique[]
     */
    public function getMissions()
    {
        return $this->missions->toArray();
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifiqueType
     */
    public function addMissionSpecifique($mission)
    {
        $this->missions->add($mission);
        return $this;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifiqueType
     */
    public function removeMissionSpecifique($mission)
    {
        $this->missions->removeElement($mission);
        return $this;
    }

    /**
     * @param MissionSpecifique $mission
     * @return boolean
     */
    public function hasMissionSpecifique($mission)
    {
        return $this->missions->contains($mission);
    }
}