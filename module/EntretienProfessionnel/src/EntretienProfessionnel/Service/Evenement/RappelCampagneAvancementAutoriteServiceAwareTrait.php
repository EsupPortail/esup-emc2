<?php

namespace EntretienProfessionnel\Service\Evenement;

trait RappelCampagneAvancementAutoriteServiceAwareTrait {

    private RappelCampagneAvancementAutoriteService $rappelCampagneAvancementAutoriteService;

    public function getRappelCampagneAvancementAutoriteService(): RappelCampagneAvancementAutoriteService
    {
        return $this->rappelCampagneAvancementAutoriteService;
    }

    public function setRappelCampagneAvancementAutoriteService(RappelCampagneAvancementAutoriteService $rappelCampagneAvancementAutoriteService): void
    {
        $this->rappelCampagneAvancementAutoriteService = $rappelCampagneAvancementAutoriteService;
    }

}