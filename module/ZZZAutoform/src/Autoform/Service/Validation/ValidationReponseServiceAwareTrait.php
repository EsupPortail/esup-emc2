<?php

namespace Autoform\Service\Validation;

trait ValidationReponseServiceAwareTrait {

    /** @var ValidationReponseService */
    private $validationReponseService;

    /**
     * @return ValidationReponseService
     */
    public function getValidationReponseService()
    {
        return $this->validationReponseService;
    }

    /**
     * @param ValidationReponseService $validationReponseService
     * @return ValidationReponseService
     */
    public function setValidationReponseService($validationReponseService)
    {
        $this->validationReponseService = $validationReponseService;
        return $this->validationReponseService;
    }


}