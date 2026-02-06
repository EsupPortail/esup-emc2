<?php

namespace EntretienProfessionnel\Service\CampagneConfigurationRecopie;

trait CampagneConfigurationRecopieServiceAwareTrait
{
    private CampagneConfigurationRecopieService $campagneConfigurationRecopieService;

    public function getCampagneConfigurationRecopieService(): CampagneConfigurationRecopieService
    {
        return $this->campagneConfigurationRecopieService;
    }

    public function setCampagneConfigurationRecopieService(CampagneConfigurationRecopieService $campagneConfigurationRecopieService): void
    {
        $this->campagneConfigurationRecopieService = $campagneConfigurationRecopieService;
    }
}
