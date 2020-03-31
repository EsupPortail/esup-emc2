<?php

namespace UnicaenValidation\Service\ValidationType;

trait ValidationTypeServiceAwareTrait {

    /** @var ValidationTypeService */
    private $validationTypeService;

    /**
     * @return ValidationTypeService
     */
    public function getValidationTypeService()
    {
        return $this->validationTypeService;
    }

    /**
     * @param ValidationTypeService $validationTypeService
     * @return ValidationTypeService
     */
    public function setValidationTypeService($validationTypeService)
    {
        $this->validationTypeService = $validationTypeService;
        return $this->validationTypeService;
    }
}