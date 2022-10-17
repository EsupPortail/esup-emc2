<?php

namespace Formation\Form\Inscription;

trait InscriptionFormAwareTrait {

    private InscriptionForm $inscriptionForm;

    /**
     * @return InscriptionForm
     */
    public function getInscriptionForm(): InscriptionForm
    {
        return $this->inscriptionForm;
    }

    /**
     * @param InscriptionForm $inscriptionForm
     */
    public function setInscriptionForm(InscriptionForm $inscriptionForm): void
    {
        $this->inscriptionForm = $inscriptionForm;
    }

}