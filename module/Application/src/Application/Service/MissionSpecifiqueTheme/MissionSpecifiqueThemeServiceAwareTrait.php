<?php

namespace Application\Service\MissionSpecifiqueTheme;

trait MissionSpecifiqueThemeServiceAwareTrait {

    /** @var MissionSpecifiqueThemeService */
    private $missionSpecifiqueThemeService;

    /**
     * @return MissionSpecifiqueThemeService
     */
    public function getMissionSpecifiqueThemeService(): MissionSpecifiqueThemeService
    {
        return $this->missionSpecifiqueThemeService;
    }

    /**
     * @param MissionSpecifiqueThemeService $missionSpecifiqueThemeService
     * @return MissionSpecifiqueThemeService
     */
    public function setMissionSpecifiqueThemeService(MissionSpecifiqueThemeService $missionSpecifiqueThemeService): MissionSpecifiqueThemeService
    {
        $this->missionSpecifiqueThemeService = $missionSpecifiqueThemeService;
        return $this->missionSpecifiqueThemeService;
    }
}