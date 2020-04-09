<?php

namespace Application\Service\MissionSpecifique;

trait MissionSpecifiqueAffectationServiceAwareTrait {

    /** @var MissionSpecifiqueAffectationService */
    private $missionSpecifiqueAffectationService;

    /**
     * @return MissionSpecifiqueAffectationService
     */
    public function getMissionSpecifiqueAffectationService()
    {
        return $this->missionSpecifiqueAffectationService;
    }

    /**
     * @param MissionSpecifiqueAffectationService $missionSpecifiqueAffectationService
     * @return MissionSpecifiqueAffectationService
     */
    public function setMissionSpecifiqueAffectationService(MissionSpecifiqueAffectationService $missionSpecifiqueAffectationService)
    {
        $this->missionSpecifiqueAffectationService = $missionSpecifiqueAffectationService;
        return $this->missionSpecifiqueAffectationService;
    }


}