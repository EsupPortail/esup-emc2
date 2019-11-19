<?php

namespace Application\Form\ValidationDemande;

trait ValidationDemandeFormAwareTrait {

    /** @var ValidationDemandeForm */
    private $validationDemandeForm;

    /**
     * @return ValidationDemandeForm
     */
    public function getValidationDemandeForm()
    {
        return $this->validationDemandeForm;
    }

    /**
     * @param ValidationDemandeForm $validationDemandeForm
     * @return ValidationDemandeForm
     */
    public function setValidationDemandeForm($validationDemandeForm)
    {
        $this->validationDemandeForm = $validationDemandeForm;
        return $this->validationDemandeForm;
    }
}