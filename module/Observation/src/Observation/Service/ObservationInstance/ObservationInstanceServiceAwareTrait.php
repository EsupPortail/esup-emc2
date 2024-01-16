<?php

namespace Observation\Service\ObservationInstance;


trait ObservationInstanceServiceAwareTrait
{
    private ObservationInstanceService $observationInstanceService;

    public function getObservationInstanceService(): ObservationInstanceService
    {
        return $this->observationInstanceService;
    }

    public function setObservationInstanceService(ObservationInstanceService $observationInstanceService): void
    {
        $this->observationInstanceService = $observationInstanceService;
    }
}