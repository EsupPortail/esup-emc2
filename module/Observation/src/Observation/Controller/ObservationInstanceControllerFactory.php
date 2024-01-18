<?php

namespace Observation\Controller;

use Observation\Form\ObservationInstance\ObservationInstanceForm;
use Observation\Service\ObservationInstance\ObservationInstanceService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;

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
         * @var ValidationInstanceService $validationInstanceService
         * @var ObservationInstanceForm  $observationInstanceForm
         */
        $observationInstanceService = $container->get(ObservationInstanceService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $observationInstanceForm = $container->get('FormElementManager')->get(ObservationInstanceForm::class);

        $controller = new ObservationInstanceController();
        $controller->setObservationInstanceService($observationInstanceService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setObservationInstanceForm($observationInstanceForm);
        return $controller;
    }
}