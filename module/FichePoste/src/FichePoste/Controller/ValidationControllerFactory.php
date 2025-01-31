<?php

namespace FichePoste\Controller;

use FichePoste\Service\FichePoste\FichePosteService;
use FichePoste\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;

class ValidationControllerFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ValidationController
    {
        /**
         * @var EtatInstanceService $etatInstanceService
         * @var FichePosteService $fichePosteService
         * @var NotificationService $notificationService
         * @var ValidationInstanceService $validationInstanceService
         */
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $notificationService = $container->get(NotificationService::class);
        $validationService = $container->get(ValidationInstanceService::class);

        $controller = new ValidationController();
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setNotificationService($notificationService);
        $controller->setValidationInstanceService($validationService);
        return $controller;
    }
}