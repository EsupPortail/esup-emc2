<?php

namespace EntretienProfessionnel\Service\Evenement;

trait RappelCampagneAvancementServiceAwareTrait {

    /** @var RappelCampagneAvancementService */
    private $rappelCampagneAvancementService;

    /**
     * @return RappelCampagneAvancementService
     */
    public function getRappelCampagneAvancementService(): RappelCampagneAvancementService
    {
        return $this->rappelCampagneAvancementService;
    }

    /**
     * @param RappelCampagneAvancementService $rappelCampagneAvancementService
     * @return RappelCampagneAvancementService
     */
    public function setRappelCampagneAvancementService(RappelCampagneAvancementService $rappelCampagneAvancementService): RappelCampagneAvancementService
    {
        $this->rappelCampagneAvancementService = $rappelCampagneAvancementService;
        return $this->rappelCampagneAvancementService;
    }


}