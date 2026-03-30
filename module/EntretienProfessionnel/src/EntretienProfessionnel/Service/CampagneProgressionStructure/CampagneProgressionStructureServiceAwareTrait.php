<?php

namespace EntretienProfessionnel\Service\CampagneProgressionStructure;

trait CampagneProgressionStructureServiceAwareTrait
{
    private CampagneProgressionStructureService $campagneProgressionStructureService;

    public function getCampagneProgressionStructureService(): CampagneProgressionStructureService
    {
        return $this->campagneProgressionStructureService;
    }

    public function setCampagneProgressionStructureService(CampagneProgressionStructureService $campagneProgressionStructureService): void
    {
        $this->campagneProgressionStructureService = $campagneProgressionStructureService;
    }

}
