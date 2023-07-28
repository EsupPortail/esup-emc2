<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Observation\ObservationForm;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Observation\ObservationService;
use Interop\Container\ContainerInterface;
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
         * @var ObservationService $observationService
         * @var ValidationInstanceService $validationInstanceService
         */
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $observationService = $container->get(ObservationService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);

        /**
         * @var ObservationForm $observationForm
         */
        $observationForm = $container->get('FormElementManager')->get(ObservationForm::class);

        $controller = new ObservationController();
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setObservationService($observationService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setObservationForm($observationForm);
        return $controller;
    }
}