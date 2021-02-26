<?php

namespace UnicaenParametre\Form\Parametre;

use Interop\Container\ContainerInterface;

class ParametreHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ParametreHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new ParametreHydrator();
        return $hydrator;
    }
} 