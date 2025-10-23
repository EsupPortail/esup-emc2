<?php

namespace Carriere\Form\FonctionType;

use Psr\Container\ContainerInterface;

class FonctionTypeHydratorFactory {

    public function __invoke(ContainerInterface $container): FonctionTypeHydrator
    {
        $hydrator = new FonctionTypeHydrator();
        return $hydrator;
    }
}
