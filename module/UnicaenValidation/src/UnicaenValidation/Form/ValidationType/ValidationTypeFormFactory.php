<?php

namespace UnicaenValidation\Form\ValidationType;

use Interop\Container\ContainerInterface;

class ValidationTypeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ValidationTypeForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ValidationTypeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ValidationTypeHydrator::class);

        /** @var ValidationTypeForm $form */
        $form = new ValidationTypeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}