<?php

namespace FicheMetier\Service\FicheMetierMission;

trait FicheMetierMissionServiceAwareTrait {

    private FicheMetierMissionService $ficheMetierMissionService;

    public function getFicheMetierMissionService(): FicheMetierMissionService
    {
        return $this->ficheMetierMissionService;
    }

    public function setFicheMetierMissionService(FicheMetierMissionService $ficheMetierMissionService): void
    {
        $this->ficheMetierMissionService = $ficheMetierMissionService;
    }


}