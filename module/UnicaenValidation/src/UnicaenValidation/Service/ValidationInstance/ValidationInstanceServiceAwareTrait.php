<?php

namespace UnicaenValidation\Service\ValidationInstance;

trait ValidationInstanceServiceAwareTrait {

    /** @var ValidationInstanceService */
    private $validationInstanceService;

    /**
     * @return ValidationInstanceService
     */
    public function getValidationInstanceService()
    {
        return $this->validationInstanceService;
    }

    /**
     * @param ValidationInstanceService $validationInstanceService
     * @return ValidationInstanceServiceAwareTrait
     */
    public function setValidationInstanceService($validationInstanceService)
    {
        $this->validationInstanceService = $validationInstanceService;
        return $this;
    }
}