<?php

namespace Observation\Form\ObservationType;

trait ObservationTypeFormAwareTrait
{
    private ObservationTypeForm $observationTypeForm;

    public function getObservationTypeForm(): ObservationTypeForm
    {
        return $this->observationTypeForm;
    }

    public function setObservationTypeForm(ObservationTypeForm $observationTypeForm): void
    {
        $this->observationTypeForm = $observationTypeForm;
    }


}