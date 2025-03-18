<?php

namespace Application\Form\ConfigurationFicheMetier;

use Interop\Container\ContainerInterface;

class ConfigurationFicheMetierHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationFicheMetierHydrator
     */
    public function __invoke(ContainerInterface $container): ConfigurationFicheMetierHydrator
    {
        $hydrator = new ConfigurationFicheMetierHydrator();
        return $hydrator;
    }
}