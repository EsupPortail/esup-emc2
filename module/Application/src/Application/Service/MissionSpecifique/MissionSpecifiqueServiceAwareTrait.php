<?php

namespace Application\Service\MissionSpecifique;

trait MissionSpecifiqueServiceAwareTrait {

    /** @var MissionSpecifiqueService  */
    private $missionSpecifiqueService;

    /**
     * @return MissionSpecifiqueService
     */
    public function getMissionSpecifiqueService()
    {
        return $this->missionSpecifiqueService;
    }

    /**
     * @param MissionSpecifiqueService $missionSpecifiqueService
     * @return MissionSpecifiqueService
     */
    public function setMissionSpecifiqueService($missionSpecifiqueService)
    {
        $this->missionSpecifiqueService = $missionSpecifiqueService;
        return $this->missionSpecifiqueService;
    }


}