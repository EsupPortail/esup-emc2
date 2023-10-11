<?php

namespace Formation\Form\Inscription;

trait InscriptionFormAwareTrait {

    private InscriptionForm $inscriptionForm;

    public function getInscriptionForm(): InscriptionForm
    {
        return $this->inscriptionForm;
    }

    public function setInscriptionForm(InscriptionForm $inscriptionForm): void
    {
        $this->inscriptionForm = $inscriptionForm;
    }

}