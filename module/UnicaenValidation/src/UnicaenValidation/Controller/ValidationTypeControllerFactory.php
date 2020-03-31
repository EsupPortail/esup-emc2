<?php

namespace UnicaenValidation\Controller;

use Psr\Container\ContainerInterface;
use UnicaenValidation\Form\ValidationType\ValidationTypeForm;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class ValidationTypeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ValidationTypeController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ValidationTypeService $validationTypeService
         */
        $validationTypeService = $container->get(ValidationTypeService::class);

        /**
         * @var ValidationTypeForm $validationTypeForm
         */
        $validationTypeForm = $container->get('FormElementManager')->get(ValidationTypeForm::class);

        /** @var ValidationTypeController $controller */
        $controller = new ValidationTypeController();
        $controller->setValidationTypeService($validationTypeService);
        $controller->setValidationTypeForm($validationTypeForm);
        return $controller;
    }
}