<?php

namespace Formation\Form\DemandeExterne;

use Psr\Container\ContainerInterface;

class DemandeExterneHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return DemandeExterneHydrator
     */
    public function __invoke(ContainerInterface $container) : DemandeExterneHydrator
    {
        $hydrator = new DemandeExterneHydrator();
        return $hydrator;
    }
}