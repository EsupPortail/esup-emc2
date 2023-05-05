<?php

namespace EntretienProfessionnel\Service\Evenement;

trait RappelCampagneAvancementServiceAwareTrait {

    private RappelCampagneAvancementService $rappelCampagneAvancementService;

    public function getRappelCampagneAvancementService(): RappelCampagneAvancementService
    {
        return $this->rappelCampagneAvancementService;
    }

    public function setRappelCampagneAvancementService(RappelCampagneAvancementService $rappelCampagneAvancementService): void
    {
        $this->rappelCampagneAvancementService = $rappelCampagneAvancementService;
    }

}