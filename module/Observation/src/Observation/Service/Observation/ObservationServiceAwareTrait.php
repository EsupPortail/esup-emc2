<?php

namespace Observation\Service\Observation;

trait ObservationServiceAwareTrait
{
    private ObservationService $observationService;

    public function getObservationService(): ObservationService
    {
        return $this->observationService;
    }

    public function setObservationService(ObservationService $observationService): void
    {
        $this->observationService = $observationService;
    }
}