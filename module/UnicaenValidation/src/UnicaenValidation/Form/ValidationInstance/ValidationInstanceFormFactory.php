<?php

namespace UnicaenValidation\Form\ValidationInstance;

use Interop\Container\ContainerInterface;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class ValidationInstanceFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ValidationTypeService $validationTypeService
         */
        $validationTypeService = $container->get(ValidationTypeService::class);

        /** @var ValidationInstanceHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ValidationInstanceHydrator::class);

        /** @var ValidationInstanceForm $form */
        $form = new ValidationInstanceForm();
        $form->setValidationTypeService($validationTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}