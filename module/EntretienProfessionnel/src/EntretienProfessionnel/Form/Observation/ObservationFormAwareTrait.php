<?php

namespace EntretienProfessionnel\Form\Observation;

trait ObservationFormAwareTrait {

    private ObservationForm $observationForm;

    public function getObservationForm() : ObservationForm
    {
        return $this->observationForm;
    }

    public function setObservationForm(ObservationForm $observationForm) : void
    {
        $this->observationForm = $observationForm;
    }

}