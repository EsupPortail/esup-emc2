<?php

namespace FicheMetier\Entity\Db\Trait;

use Doctrine\Common\Collections\Collection;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionElement;

trait HasMissionsPrincipalesTrait
{
    private Collection $missions;

    /** @return MissionElement[] */
    public function getMissions(bool $withHisto = false): array
    {
        $missions =  $this->missions->toArray();
        if (!$withHisto) {
            $missions = array_filter($missions, function (MissionElement $mission) {
                return $mission->getMission()->estNonHistorise();
            });
        }
        return $missions;
    }

    public function addMission(MissionElement $ficheMetierMission): void
    {
        $this->missions->add($ficheMetierMission);
    }

    public function removeMission(MissionElement $ficheMetierMission): void
    {
        $this->missions->removeElement($ficheMetierMission);
    }

    public function clearMissions(): void
    {
        $this->missions->clear();
    }

    public function hasMission(Mission $mission): bool
    {
        /** @var MissionElement[] $missions */
        $missions = $this->missions->toArray();
        foreach ($missions as $fmMission) {
            if ($fmMission->getMission() === $mission) return true;
        }
        return false;
    }
}