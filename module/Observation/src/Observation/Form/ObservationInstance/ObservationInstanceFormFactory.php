<?php

namespace Observation\Form\ObservationInstance;

use Observation\Service\ObservationType\ObservationTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ObservationInstanceFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservationInstanceForm
    {
        /**
         * @var ObservationTypeService $observationTypeService
         * @var ObservationInstanceHydrator $hydrator
         */
        $observationTypeService = $container->get(ObservationTypeService::class);
        $hydrator = $container->get('HydratorManager')->get(ObservationInstanceHydrator::class);

        $form = new ObservationInstanceForm();
        $form->setObservationTypeService($observationTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}