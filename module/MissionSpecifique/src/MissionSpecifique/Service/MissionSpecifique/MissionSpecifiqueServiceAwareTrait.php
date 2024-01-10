<?php

namespace MissionSpecifique\Service\MissionSpecifique;

trait MissionSpecifiqueServiceAwareTrait
{
    private MissionSpecifiqueService $missionSpecifiqueService;

    public function getMissionSpecifiqueService(): MissionSpecifiqueService
    {
        return $this->missionSpecifiqueService;
    }

    public function setMissionSpecifiqueService(MissionSpecifiqueService $missionSpecifiqueService): void
    {
        $this->missionSpecifiqueService = $missionSpecifiqueService;
    }
}