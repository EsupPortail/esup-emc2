<?php

namespace Application\Service\Validation;

trait ValidationTypeServiceAwareTrait {

    /** @var ValidationTypeService $validationTypeService */
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