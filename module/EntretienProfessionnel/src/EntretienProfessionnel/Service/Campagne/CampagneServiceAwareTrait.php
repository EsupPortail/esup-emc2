<?php

namespace EntretienProfessionnel\Service\Campagne;

trait CampagneServiceAwareTrait
{

    private CampagneService $campagneService;

    public function getCampagneService(): CampagneService
    {
        return $this->campagneService;
    }

    public function setCampagneService(CampagneService $campagneService): void
    {
        $this->campagneService = $campagneService;
    }

}