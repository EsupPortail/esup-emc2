<?php

namespace UnicaenNote\Form\Type;

use Interop\Container\ContainerInterface;

class TypeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return TypeHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new TypeHydrator();
        return $hydrator;
    }
}