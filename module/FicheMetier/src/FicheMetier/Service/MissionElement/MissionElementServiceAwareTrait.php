<?php

namespace FicheMetier\Service\MissionElement;

trait MissionElementServiceAwareTrait {

    private MissionElementService $missionElementService;

    public function getMissionElementService(): MissionElementService
    {
        return $this->missionElementService;
    }

    public function setMissionElementService(MissionElementService $missionElementService): void
    {
        $this->missionElementService = $missionElementService;
    }

}
