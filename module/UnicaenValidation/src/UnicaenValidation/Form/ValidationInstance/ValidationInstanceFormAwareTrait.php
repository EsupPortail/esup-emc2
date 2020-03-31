<?php

namespace UnicaenValidation\Form\ValidationInstance;

trait ValidationInstanceFormAwareTrait {

    /** @var ValidationInstanceForm */
    private $validationInstancForm;

    /**
     * @return ValidationInstanceForm
     */
    public function getValidationInstancForm()
    {
        return $this->validationInstancForm;
    }

    /**
     * @param ValidationInstanceForm $validationInstancForm
     * @return ValidationInstanceForm
     */
    public function setValidationInstancForm($validationInstancForm)
    {
        $this->validationInstancForm = $validationInstancForm;
        return $this->validationInstancForm;
    }

}