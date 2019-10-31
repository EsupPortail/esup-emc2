<?php

namespace Application\Service\Validation;

trait ValidationDemandeServiceAwareTrait {

    /** @var ValidationDemandeService $validationDemandeService */
    private $validationDemandeService;

    /**
     * @return ValidationDemandeService
     */
    public function getValidationDemandeService()
    {
        return $this->validationDemandeService;
    }

    /**
     * @param ValidationDemandeService $validationDemandeService
     * @return ValidationDemandeService
     */
    public function setValidationDemandeService($validationDemandeService)
    {
        $this->validationDemandeService = $validationDemandeService;
        return $this->validationDemandeService;
    }
}