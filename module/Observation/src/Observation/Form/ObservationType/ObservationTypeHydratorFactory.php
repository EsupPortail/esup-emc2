<?php

namespace Observation\Form\ObservationType;

use Psr\Container\ContainerInterface;

class ObservationTypeHydratorFactory {

    public function __invoke(ContainerInterface $container): ObservationTypeHydrator
    {
        $hydrator = new ObservationTypeHydrator();
        return $hydrator;
    }
}