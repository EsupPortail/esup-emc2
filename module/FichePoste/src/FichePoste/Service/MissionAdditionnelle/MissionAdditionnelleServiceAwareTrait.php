<?php

namespace FichePoste\Service\MissionAdditionnelle;

trait MissionAdditionnelleServiceAwareTrait {

    private MissionAdditionnelleService $missionAdditionnelleService;

    public function getMissionAdditionnelleService(): MissionAdditionnelleService
    {
        return $this->missionAdditionnelleService;
    }

    public function setMissionAdditionnelleService(MissionAdditionnelleService $missionAdditionnelleService): void
    {
        $this->missionAdditionnelleService = $missionAdditionnelleService;
    }

}