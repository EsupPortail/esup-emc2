<?php

namespace FicheMetier\Entity\Db\Interface;

use FicheMetier\Entity\Db\FicheMetierMission;
use FicheMetier\Entity\Db\Mission;

interface HasMissionsPrincipalesInterface
{
    /** @return FicheMetierMission[] */
    public function getMissions(): array;
    public function addMission(FicheMetierMission $ficheMetierMission): void;
    public function removeMission(FicheMetierMission $ficheMetierMission): void;
    public function clearMissions(): void;
    public function hasMission(Mission $mission): bool;

}