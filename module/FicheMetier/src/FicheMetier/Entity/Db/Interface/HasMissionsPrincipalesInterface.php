<?php

namespace FicheMetier\Entity\Db\Interface;

use FicheMetier\Entity\Db\MissionElement;
use FicheMetier\Entity\Db\Mission;

interface HasMissionsPrincipalesInterface
{
    /** @return MissionElement[] */
    public function getMissions(bool $withHisto = false): array;
    public function addMission(MissionElement $missionElement): void;
    public function removeMission(MissionElement $missionElement): void;
    public function clearMissions(): void;
    public function hasMission(Mission $mission): bool;

}