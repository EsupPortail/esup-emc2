<?php

namespace Carriere\Form\Niveau;

use Interop\Container\ContainerInterface;

class NiveauHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauHydrator
     */
    public function __invoke(ContainerInterface $container) : NiveauHydrator
    {
        $hydrator = new NiveauHydrator();
        return $hydrator;
    }
}