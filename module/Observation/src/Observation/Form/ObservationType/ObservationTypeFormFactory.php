<?php

namespace Observation\Form\ObservationType;

use Observation\Service\ObservationType\ObservationTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ObservationTypeFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservationTypeForm
    {
        /**
         * @var ObservationTypeService $observationTypeService
         * @var ObservationTypeHydrator $observationTypeHydrator
         */
        $observationTypeService = $container->get(ObservationTypeService::class);
        $observationTypeHydrator = $container->get('HydratorManager')->get(ObservationTypeHydrator::class);

        $form = new ObservationTypeForm();
        $form->setObservationTypeService($observationTypeService);
        $form->setHydrator($observationTypeHydrator);
        return $form;
    }
}