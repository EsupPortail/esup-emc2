<?php

namespace Autoform\Service\Validation;

trait ValidationServiceAwareTrait {

    /** @var ValidationService */
    private  $validationService;

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