<?php

namespace Application\Form\EntretienProfessionnelObservation;

use Interop\Container\ContainerInterface;

class EntretienProfessionnelObservationHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelObservationHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntretienProfessionnelObservationHydrator $hydrator */
        $hydrator = new EntretienProfessionnelObservationHydrator();
        return $hydrator;
    }
}