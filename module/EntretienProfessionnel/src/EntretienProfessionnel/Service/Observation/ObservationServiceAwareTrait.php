<?php

namespace EntretienProfessionnel\Service\Observation;

trait ObservationServiceAwareTrait {

    /** @var ObservationService */
    private $observationService;

    /**
     * @return ObservationService
     */
    public function getObservationService() : ObservationService
    {
        return $this->observationService;
    }

    /**
     * @param ObservationService $observationService
     * @return ObservationService
     */
    public function setObservationService(ObservationService $observationService) : ObservationService
    {
        $this->observationService = $observationService;
        return $this->observationService;
    }

}