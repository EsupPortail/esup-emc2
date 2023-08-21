<?php

namespace FicheMetier\Service\MissionActivite;

trait MissionActiviteServiceAwareTrait {

    private MissionActiviteService $missionActiviteService;

    public function getMissionActiviteService(): MissionActiviteService
    {
        return $this->missionActiviteService;
    }

    public function setMissionActiviteService(MissionActiviteService $missionActiviteService): void
    {
        $this->missionActiviteService = $missionActiviteService;
    }


}