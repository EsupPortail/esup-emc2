<?php

namespace Observation\Form\ObservationInstance;

use Observation\Service\ObservationType\ObservationTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ObservationInstanceHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservationInstanceHydrator
    {
        /** @var ObservationTypeService $observationTypeService */
        $observationTypeService = $container->get(ObservationTypeService::class);

        $hydrator = new ObservationInstanceHydrator();
        $hydrator->setObservationTypeService($observationTypeService);
        return $hydrator;
    }
}