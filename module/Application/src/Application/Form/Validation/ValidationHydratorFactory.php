<?php

namespace Application\Form\Validation;

use Application\Service\Validation\ValidationValeurService;
use Interop\Container\ContainerInterface;

class ValidationHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ValidationValeurService $validationValeurService
         */
        $validationValeurService = $container->get(ValidationValeurService::class);

        /** @var ValidationHydrator $hydrator */
        $hydrator = new ValidationHydrator();
        $hydrator->setValidationValeurService($validationValeurService);
        return $hydrator;
    }
}