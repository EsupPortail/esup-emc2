<?php

namespace EntretienProfessionnel\Service\Evenement;

trait RappelPasObservationServiceAwareTrait {

    /** @var RappelPasObservationService */
    private $rappelPasObservationService;

    /**
     * @return RappelPasObservationService
     */
    public function getRappelPasObservationService(): RappelPasObservationService
    {
        return $this->rappelPasObservationService;
    }

    /**
     * @param RappelPasObservationService $rappelPasObservationService
     * @return RappelPasObservationService
     */
    public function setRappelPasObservationService(RappelPasObservationService $rappelPasObservationService): RappelPasObservationService
    {
        $this->rappelPasObservationService = $rappelPasObservationService;
        return $this->rappelPasObservationService;
    }
}