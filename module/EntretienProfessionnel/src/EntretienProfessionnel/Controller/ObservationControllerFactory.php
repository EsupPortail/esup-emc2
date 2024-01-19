<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Interop\Container\ContainerInterface;
use Observation\Form\ObservationInstance\ObservationInstanceForm;
use Observation\Service\ObservationInstance\ObservationInstanceService;
use Observation\Service\ObservationType\ObservationTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;

class ObservationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ObservationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ObservationController
    {
        /**
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var ObservationInstanceService $observationInstanceService
         * @var ObservationTypeService $observationTypeService
         * @var ValidationInstanceService $validationInstanceService
         */
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $observationInstanceService = $container->get(ObservationInstanceService::class);
        $observationTypeService = $container->get(ObservationTypeService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);

        /**
         * @var ObservationInstanceForm $observationInstanceForm
         */
        $observationInstanceForm = $container->get('FormElementManager')->get(ObservationInstanceForm::class);

        $controller = new ObservationController();
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setObservationInstanceService($observationInstanceService);
        $controller->setObservationTypeService($observationTypeService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setObservationInstanceForm($observationInstanceForm);
        return $controller;
    }
}