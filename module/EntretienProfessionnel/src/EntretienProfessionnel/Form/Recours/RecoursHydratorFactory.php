<?php

namespace EntretienProfessionnel\Form\Recours;

use Psr\Container\ContainerInterface;

class RecoursHydratorFactory {

    public function __invoke(ContainerInterface $container): RecoursHydrator
    {
        $hydrator = new RecoursHydrator();
        return $hydrator;
    }
}