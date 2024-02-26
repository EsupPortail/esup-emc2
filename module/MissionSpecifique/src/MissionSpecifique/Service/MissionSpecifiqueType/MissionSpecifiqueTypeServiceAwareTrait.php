<?php

namespace MissionSpecifique\Service\MissionSpecifiqueType;

trait MissionSpecifiqueTypeServiceAwareTrait {

    private MissionSpecifiqueTypeService $missionSpecifiqueTypeService;

    public function getMissionSpecifiqueTypeService(): MissionSpecifiqueTypeService
    {
        return $this->missionSpecifiqueTypeService;
    }

    public function setMissionSpecifiqueTypeService(MissionSpecifiqueTypeService $missionSpecifiqueTypeService): MissionSpecifiqueTypeService
    {
        $this->missionSpecifiqueTypeService = $missionSpecifiqueTypeService;
        return $this->missionSpecifiqueTypeService;
    }


}