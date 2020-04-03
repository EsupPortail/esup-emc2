<?php

namespace Application\Form\ConfigurationEntretienProfessionnel;

use Interop\Container\ContainerInterface;

class ConfigurationEntretienProfessionnelHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationEntretienProfessionnelHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ConfigurationEntretienProfessionnelHydrator $hydrator */
        $hydrator = new ConfigurationEntretienProfessionnelHydrator();
        return $hydrator;
    }
}
