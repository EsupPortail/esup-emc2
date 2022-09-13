<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Form\DemandeExterne\DemandeExterneForm;
use Formation\Form\Inscription\InscriptionForm;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;

class DemandeExterneControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return DemandeExterneController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : DemandeExterneController
    {
        /**
         * @var AgentService $agentService
         * @var DemandeExterneService $demandeExterneService
         * @var EtatService $etatService
         * @var NotificationService $notificationService
         */
        $agentService = $container->get(AgentService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $etatService = $container->get(EtatService::class);
        $notificationService = $container->get(NotificationService::class);

        /**
         * @var DemandeExterneForm $demandeExterneForm
         * @var InscriptionForm $inscriptionForm
         */
        $demandeExterneForm = $container->get('FormElementManager')->get(DemandeExterneForm::class);
        $inscriptionForm = $container->get('FormElementManager')->get(InscriptionForm::class);

        $controller = new DemandeExterneController();
        $controller->setAgentService($agentService);
        $controller->setDemandeExterneService($demandeExterneService);
        $controller->setEtatService($etatService);
        $controller->setNotificationService($notificationService);
        $controller->setDemandeExterneForm($demandeExterneForm);
        $controller->setInscriptionForm($inscriptionForm);
        return $controller;
    }
}