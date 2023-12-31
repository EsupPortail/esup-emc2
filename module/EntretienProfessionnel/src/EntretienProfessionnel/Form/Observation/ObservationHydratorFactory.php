<?php

namespace EntretienProfessionnel\Form\Observation;

use Interop\Container\ContainerInterface;

class ObservationHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ObservationHydrator
     */
    public function __invoke(ContainerInterface $container) : ObservationHydrator
    {
        $hydrator = new ObservationHydrator();
        return $hydrator;
    }
}