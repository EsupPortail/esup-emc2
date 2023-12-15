<?php

namespace Formation\Controller;

use Formation\Form\Inscription\InscriptionForm;
use Formation\Form\InscriptionFrais\InscriptionFraisForm;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\InscriptionFrais\InscriptionFraisService;
use Formation\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;

class InscriptionControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return InscriptionController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionController
    {
        /**
         * @var EtatInstanceService $etatInstanceService
         * @var FormationInstanceService $formationInstanceService
         * @var InscriptionService $inscriptionService
         * @var NotificationService $notificationService
         * @var InscriptionForm $inscriptionForm
         */
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $notificationService = $container->get(NotificationService::class);
        $inscriptionForm = $container->get('FormElementManager')->get(InscriptionForm::class);

        /**
         * @var InscriptionFraisService $inscriptionFraisService
         * @var InscriptionFraisForm $inscriptionFraisForm
         */
        $inscriptionFraisService = $container->get(InscriptionFraisService::class);
        $inscriptionFraisForm = $container->get('FormElementManager')->get(InscriptionFraisForm::class);

        $controller = new InscriptionController();
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setNotificationService($notificationService);
        $controller->setInscriptionForm($inscriptionForm);

        $controller->setInscriptionFraisService($inscriptionFraisService);
        $controller->setInscriptionFraisForm($inscriptionFraisForm);

        return $controller;
    }
}