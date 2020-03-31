<?php

namespace UnicaenValidation\Form\ValidationType;

trait ValidationTypeFormAwareTrait {

    /** @var ValidationTypeForm */
    private $validationTypeForm;

    /**
     * @return ValidationTypeForm
     */
    public function getValidationTypeForm()
    {
        return $this->validationTypeForm;
    }

    /**
     * @param ValidationTypeForm $validationTypeForm
     * @return ValidationTypeForm
     */
    public function setValidationTypeForm($validationTypeForm)
    {
        $this->validationTypeForm = $validationTypeForm;
        return $this->validationTypeForm;
    }
}