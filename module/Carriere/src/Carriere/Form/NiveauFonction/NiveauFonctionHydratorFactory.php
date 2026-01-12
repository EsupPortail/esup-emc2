<?php

namespace Carriere\Form\NiveauFonction;

use Psr\Container\ContainerInterface;

class NiveauFonctionHydratorFactory {

    public function __invoke(ContainerInterface $container): NiveauFonctionHydrator
    {
        $hydrator = new NiveauFonctionHydrator();
        return $hydrator;
    }
}
