<?php

namespace EntretienProfessionnel\Form\Observation;

trait ObservationFormAwareTrait {

    /** @var ObservationForm */
    private $observationForm;

    /**
     * @return ObservationForm
     */
    public function getObservationForm() : ObservationForm
    {
        return $this->observationForm;
    }

    /**
     * @param ObservationForm $observationForm
     * @return ObservationForm
     */
    public function setObservationForm(ObservationForm $observationForm) : ObservationForm
    {
        $this->observationForm = $observationForm;
        return $this->observationForm;
    }

}