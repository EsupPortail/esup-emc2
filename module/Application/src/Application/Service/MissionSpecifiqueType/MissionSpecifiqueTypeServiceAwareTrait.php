<?php

namespace Application\Service\MissionSpecifiqueType;

trait MissionSpecifiqueTypeServiceAwareTrait {

    /** @var MissionSpecifiqueTypeService */
    private $missionSpecifiqueTypeService;

    /**
     * @return MissionSpecifiqueTypeService
     */
    public function getMissionSpecifiqueTypeService(): MissionSpecifiqueTypeService
    {
        return $this->missionSpecifiqueTypeService;
    }

    /**
     * @param MissionSpecifiqueTypeService $missionSpecifiqueTypeService
     * @return MissionSpecifiqueTypeService
     */
    public function setMissionSpecifiqueTypeService(MissionSpecifiqueTypeService $missionSpecifiqueTypeService): MissionSpecifiqueTypeService
    {
        $this->missionSpecifiqueTypeService = $missionSpecifiqueTypeService;
        return $this->missionSpecifiqueTypeService;
    }


}