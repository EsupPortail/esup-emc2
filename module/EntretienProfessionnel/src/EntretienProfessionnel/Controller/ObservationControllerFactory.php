<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Observation\ObservationForm;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Observation\ObservationService;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;

class ObservationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ObservationController
     */
    public function __invoke(ContainerInterface $container)
    {
//    use ObservationFormAwareTrait;
        /**
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var MailingService $mailingService
         * @var ObservationService $observationService
         */
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $mailingService = $container->get(MailingService::class);
        $observationService = $container->get(ObservationService::class);

        /**
         * @var ObservationForm $observationForm
         */
        $observationForm = $container->get('FormElementManager')->get(ObservationForm::class);

        $controller = new ObservationController();
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setMailingService($mailingService);
        $controller->setObservationService($observationService);
        $controller->setObservationForm($observationForm);
        return $controller;
    }
}