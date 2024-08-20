<?php

namespace Formation\Service\Evenement;

use Formation\Service\Notification\NotificationService;
use Formation\Service\Session\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NotificationFormationsOuvertesServiceFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): NotificationFormationsOuvertesService
    {
        /**
         * @var SessionService $sessionService
         * @var NotificationService $notificationService
         */
        $sessionService = $container->get(SessionService::class);
        $notificationService = $container->get(NotificationService::class);

        $service = new NotificationFormationsOuvertesService();
        $service->setSessionService($sessionService);
        $service->setNotificationService($notificationService);
        return $service;
    }
}