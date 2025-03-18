<?php

namespace EntretienProfessionnel\Form\ConfigurationRecopie;

use Interop\Container\ContainerInterface;

class ConfigurationRecopieHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationRecopieHydrator
     */
    public function __invoke(ContainerInterface $container) : ConfigurationRecopieHydrator
    {
        $hydrator = new ConfigurationRecopieHydrator();
        return $hydrator;
    }
}
