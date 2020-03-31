<?php

namespace UnicaenValidation\Controller;

use Interop\Container\ContainerInterface;
use UnicaenValidation\Form\ValidationInstance\ValidationInstanceForm;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class ValidationInstanceControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ValidationInstanceController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         */
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);

        /**
         * @var ValidationInstanceForm $validationInstanceForm
         */
        $validationInstanceForm = $container->get('FormElementManager')->get(ValidationInstanceForm::class);

        /** @var ValidationInstanceController $controller */
        $controller = new ValidationInstanceController();
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setValidationInstancForm($validationInstanceForm);
        $controller->setValidationTypeService($validationTypeService);
        return $controller;
    }
}