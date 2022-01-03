<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Observation\ObservationForm;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Observation\ObservationService;
use Interop\Container\ContainerInterface;

class ObservationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ObservationController
     */
    public function __invoke(ContainerInterface $container) : ObservationController
    {
        /**
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var ObservationService $observationService
         */
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $observationService = $container->get(ObservationService::class);

        /**
         * @var ObservationForm $observationForm
         */
        $observationForm = $container->get('FormElementManager')->get(ObservationForm::class);

        $controller = new ObservationController();
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setObservationService($observationService);
        $controller->setObservationForm($observationForm);
        return $controller;
    }
}