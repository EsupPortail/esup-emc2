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
        /** @var ConfigurationRecopieHydrator $hydrator */
        $hydrator = new ConfigurationRecopieHydrator();
        return $hydrator;
    }
}
