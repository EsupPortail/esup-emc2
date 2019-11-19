<?php

namespace Application\Form\Validation;

trait ValidateurFormAwareTrait {

    /** @var ValidationForm */
    private $validationForm;

    /**
     * @return ValidationForm
     */
    public function getValidationForm()
    {
        return $this->validationForm;
    }

    /**
     * @param ValidationForm $validationForm
     * @return ValidationForm
     */
    public function setValidationForm($validationForm)
    {
        $this->validationForm = $validationForm;
        return $this->validationForm;
    }


}