<?php

namespace Application\Form\EntretienProfessionnelObservation;

trait EntretienProfessionnelObservationFormAwareTrait {

    /** @var EntretienProfessionnelObservationForm */
    private $entretienProfessionnelObservationForm;

    /**
     * @return EntretienProfessionnelObservationForm
     */
    public function getEntretienProfessionnelObservationForm()
    {
        return $this->entretienProfessionnelObservationForm;
    }

    /**
     * @param EntretienProfessionnelObservationForm $entretienProfessionnelObservationForm
     * @return EntretienProfessionnelObservationFormAwareTrait
     */
    public function setEntretienProfessionnelObservationForm($entretienProfessionnelObservationForm)
    {
        $this->entretienProfessionnelObservationForm = $entretienProfessionnelObservationForm;
        return $this;
    }

}