<?php

namespace EntretienProfessionnel\Service\CampagneConfigurationPresaisie;

trait CampagneConfigurationPresaisieServiceAwareTrait
{
    private CampagneConfigurationPresaisieService $campagneConfigurationPresaisieService;

    public function getCampagneConfigurationPresaisieService(): CampagneConfigurationPresaisieService
    {
        return $this->campagneConfigurationPresaisieService;
    }

    public function setCampagneConfigurationPresaisieService(CampagneConfigurationPresaisieService $campagneConfigurationPresaisieService): void
    {
        $this->campagneConfigurationPresaisieService = $campagneConfigurationPresaisieService;
    }

}
