<?php

namespace Formation\Form\EnqueteReponse;

trait EnqueteReponseFormAwareTrait {

    private EnqueteReponseForm $enqueteReponseForm;

    public function getEnqueteReponseForm(): EnqueteReponseForm
    {
        return $this->enqueteReponseForm;
    }

    public function setEnqueteReponseForm(EnqueteReponseForm $enqueteReponseForm): void
    {
        $this->enqueteReponseForm = $enqueteReponseForm;
    }
}