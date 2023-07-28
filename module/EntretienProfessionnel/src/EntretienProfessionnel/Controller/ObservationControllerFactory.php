<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Observation\ObservationForm;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Observation\ObservationService;
use Interop\Container\ContainerInterface;
use UnicaenEtat\src\UnicaenEtat\Service\Etat\EtatService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;

class ObservationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ObservationController
     */
    public function __invoke(ContainerInterface $container) : ObservationController
    {
        /**
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var UnicaenEtat\src\UnicaenEtat\Service\Etat\EtatService $etatService
         * @var ObservationService $observationService
         * @var ValidationInstanceService $validationInstanceService
         */
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $etatService = $container->get(EtatService::class);
        $observationService = $container->get(ObservationService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);

        /**
         * @var ObservationForm $observationForm
         */
        $observationForm = $container->get('FormElementManager')->get(ObservationForm::class);

        $controller = new ObservationController();
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setEtatService($etatService);
        $controller->setObservationService($observationService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setObservationForm($observationForm);
        return $controller;
    }
}