<?php

namespace UnicaenValidation\Form\ValidationInstance;

use Interop\Container\ContainerInterface;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class ValidationInstanceHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ValidationInstanceHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ValidationTypeService $validationTypeService
         */
        $validationTypeService = $container->get(ValidationTypeService::class);

        /** @var ValidationInstanceHydrator $hydrator */
        $hydrator = new ValidationInstanceHydrator();
        $hydrator->setValidationTypeService($validationTypeService);
        return $hydrator;
    }
}