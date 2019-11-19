<?php

namespace Application\Form\Validation;

use Application\Service\Validation\ValidationValeurService;
use Interop\Container\ContainerInterface;

class ValidationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ValidationValeurService $validationValeurService
         * @var ValidationHydrator $hydrator
         */
        $validationValeurService = $container->get(ValidationValeurService::class);
        $hydrator = $container->get('HydratorManager')->get(ValidationHydrator::class);

        /** @var ValidationForm $form */
        $form = new ValidationForm();
        $form->setValidationValeurService($validationValeurService);
        $form->setHydrator($hydrator);
        return $form;
    }
}