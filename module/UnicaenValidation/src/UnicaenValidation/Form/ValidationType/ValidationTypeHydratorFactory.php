<?php

namespace UnicaenValidation\Form\ValidationType;

use Interop\Container\ContainerInterface;

class ValidationTypeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ValidationTypeHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ValidationTypeHydrator $hydrator */
        $hydrator = new ValidationTypeHydrator();
        return $hydrator;
    }
}