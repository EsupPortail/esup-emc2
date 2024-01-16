<?php

namespace Observation\Controller;

use Observation\Form\ObservationInstance\ObservationInstanceForm;
use Observation\Service\ObservationInstance\ObservationInstanceService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ObservationInstanceControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservationInstanceController
    {
        /**
         * @var ObservationInstanceService $observationInstanceService
         * @var ObservationInstanceForm  $observationInstanceForm
         */
        $observationInstanceService = $container->get(ObservationInstanceService::class);
        $observationInstanceForm = $container->get('FormElementManager')->get(ObservationInstanceForm::class);

        $controller = new ObservationInstanceController();
        $controller->setObservationInstanceService($observationInstanceService);
        $controller->setObservationInstanceForm($observationInstanceForm);
        return $controller;
    }
}