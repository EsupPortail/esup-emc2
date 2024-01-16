<?php

namespace Observation\Form\ObservationInstance;

trait ObservationInstanceFormAwareTrait
{
    private ObservationInstanceForm $observationInstanceForm;

    public function getObservationInstanceForm(): ObservationInstanceForm
    {
        return $this->observationInstanceForm;
    }

    public function setObservationInstanceForm(ObservationInstanceForm $observationInstanceForm): void
    {
        $this->observationInstanceForm = $observationInstanceForm;
    }

}