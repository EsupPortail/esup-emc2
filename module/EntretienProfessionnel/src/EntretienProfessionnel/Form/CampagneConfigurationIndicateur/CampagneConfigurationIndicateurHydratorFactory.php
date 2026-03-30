<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationIndicateur;

use Psr\Container\ContainerInterface;

class CampagneConfigurationIndicateurHydratorFactory {

    public function __invoke(ContainerInterface $container): CampagneConfigurationIndicateurHydrator
    {
        $hydrator = new CampagneConfigurationIndicateurHydrator();
        return $hydrator;
    }
}
