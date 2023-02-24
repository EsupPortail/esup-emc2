<?php

namespace FicheMetier\Service\MissionPrincipale;

trait MissionPrincipaleServiceAwareTrait {

    private MissionPrincipaleService $missionPrincipaleService;

    public function getMissionPrincipaleService(): MissionPrincipaleService
    {
        return $this->missionPrincipaleService;
    }

    public function setMissionPrincipaleService(MissionPrincipaleService $missionPrincipaleService): void
    {
        $this->missionPrincipaleService = $missionPrincipaleService;
    }

}