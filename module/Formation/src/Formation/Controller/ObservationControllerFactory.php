<?php

namespace Formation\Controller;

use Formation\Service\DemandeExterne\DemandeExterneService;
use Psr\Container\ContainerInterface;
use UnicaenObservation\Form\ObservationInstance\ObservationInstanceForm;
use UnicaenObservation\Service\ObservationInstance\ObservationInstanceService;
use UnicaenObservation\Service\ObservationType\ObservationTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ObservationControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ObservationController
    {
        /**
         * @var DemandeExterneService $demandeExterneService
         * @var ObservationInstanceService $observationInstanceService
         * @var ObservationTypeService $observationTypeService
         */
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $observationInstanceService = $container->get(ObservationInstanceService::class);
        $observationTypeService = $container->get(ObservationTypeService::class);

        /**
         * @var ObservationInstanceForm $observationInstanceForm
         */
        $observationInstanceForm = $container->get('FormElementManager')->get(ObservationInstanceForm::class);

        $controller = new ObservationController();
        $controller->setDemandeExterneService($demandeExterneService);
        $controller->setObservationInstanceService($observationInstanceService);
        $controller->setObservationTypeService($observationTypeService);
        $controller->setObservationInstanceForm($observationInstanceForm);
        return $controller;
    }
}