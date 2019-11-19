<?php

namespace Application\Service\Validation;

trait ValidationServiceAwareTrait {

    /** @var ValidationService $validationService */
    private $validationService;

    /**
     * @return ValidationService
     */
    public function getValidationService()
    {
        return $this->validationService;
    }

    /**
     * @param ValidationService $validationService
     * @return ValidationService
     */
    public function setValidationService($validationService)
    {
        $this->validationService = $validationService;
        return $this->validationService;
    }


}