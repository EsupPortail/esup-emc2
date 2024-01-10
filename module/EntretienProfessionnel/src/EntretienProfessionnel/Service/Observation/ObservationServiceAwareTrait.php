<?php

namespace EntretienProfessionnel\Service\Observation;

trait ObservationServiceAwareTrait
{

    private ObservationService $observationService;

    public function getObservationService(): ObservationService
    {
        return $this->observationService;
    }

    public function setObservationService(ObservationService $observationService): ObservationService
    {
        $this->observationService = $observationService;
        return $this->observationService;
    }

}