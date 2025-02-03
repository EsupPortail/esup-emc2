<?php

namespace UnicaenContact\Form\Type;

use Psr\Container\ContainerInterface;

class TypeHydratorFactory {

    public function __invoke(ContainerInterface $container): TypeHydrator
    {
        $hydrator = new TypeHydrator();
        return $hydrator;
    }
}