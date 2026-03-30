<?php

namespace EntretienProfessionnel\Service\CampagneConfigurationIndicateur;

trait CampagneConfigurationIndicateurServiceAwareTrait
{

    private CampagneConfigurationIndicateurService $campagneConfigurationIndicateurService;

    public function getCampagneConfigurationIndicateurService(): CampagneConfigurationIndicateurService
    {
        return $this->campagneConfigurationIndicateurService;
    }

    public function setCampagneConfigurationIndicateurService(CampagneConfigurationIndicateurService $campagneConfigurationIndicateurService): void
    {
        $this->campagneConfigurationIndicateurService = $campagneConfigurationIndicateurService;
    }

}