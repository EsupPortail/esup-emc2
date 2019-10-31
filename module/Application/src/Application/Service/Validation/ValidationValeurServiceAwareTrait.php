<?php

namespace Application\Service\Validation;

trait ValidationValeurServiceAwareTrait {

    /** @var ValidationValeurService $validationValeurService */
    private $validationValeurService;

    /**
     * @return ValidationValeurService
     */
    public function getValidationValeurService()
    {
        return $this->validationValeurService;
    }

    /**
     * @param ValidationValeurService $validationValeurService
     * @return ValidationValeurService
     */
    public function setValidationValeurService($validationValeurService)
    {
        $this->validationValeurService = $validationValeurService;
        return $this->validationValeurService;
    }



}