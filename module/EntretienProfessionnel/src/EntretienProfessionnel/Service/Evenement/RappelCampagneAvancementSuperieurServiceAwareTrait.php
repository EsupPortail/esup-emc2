<?php

namespace EntretienProfessionnel\Service\Evenement;

trait RappelCampagneAvancementSuperieurServiceAwareTrait {

    private RappelCampagneAvancementSuperieurService $rappelCampagneAvancementSuperieurService;

    public function getRappelCampagneAvancementSuperieurService(): RappelCampagneAvancementSuperieurService
    {
        return $this->rappelCampagneAvancementSuperieurService;
    }

    public function setRappelCampagneAvancementSuperieurService(RappelCampagneAvancementSuperieurService $rappelCampagneAvancementSuperieurService): void
    {
        $this->rappelCampagneAvancementSuperieurService = $rappelCampagneAvancementSuperieurService;
    }

}