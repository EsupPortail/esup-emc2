<?php

namespace MissionSpecifique\Service\MissionSpecifiqueTheme;

trait MissionSpecifiqueThemeServiceAwareTrait {

    private MissionSpecifiqueThemeService $missionSpecifiqueThemeService;

    public function getMissionSpecifiqueThemeService(): MissionSpecifiqueThemeService
    {
        return $this->missionSpecifiqueThemeService;
    }

    public function setMissionSpecifiqueThemeService(MissionSpecifiqueThemeService $missionSpecifiqueThemeService): void
    {
        $this->missionSpecifiqueThemeService = $missionSpecifiqueThemeService;
    }
}