<?php

namespace Observation\Controller;

use Observation\Form\ObservationType\ObservationTypeForm;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Observation\Service\ObservationType\ObservationTypeService;

class ObservationTypeControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservationTypeController
    {
        /**
         * @var ObservationTypeService $observationTypeService
         */
        $observationTypeService = $container->get(ObservationTypeService::class);

        /**
         * @var ObservationTypeForm $observationTypeForm
         */
        $observationTypeForm = $container->get('FormElementManager')->get(ObservationTypeForm::class);

        $controller = new ObservationTypeController();
        $controller->setObservationTypeService($observationTypeService);
        $controller->setObservationTypeForm($observationTypeForm);
        return $controller;
    }

}