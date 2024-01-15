<?php

namespace Observation\Service\ObservationType;

trait ObservationTypeServiceAwareTrait
{
    private ObservationTypeService $observationTypeService;

    public function getObservationTypeService(): ObservationTypeService
    {
        return $this->observationTypeService;
    }

    public function setObservationTypeService(ObservationTypeService $observationTypeService): void
    {
        $this->observationTypeService = $observationTypeService;
    }
}