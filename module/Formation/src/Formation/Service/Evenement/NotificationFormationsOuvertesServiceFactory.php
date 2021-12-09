<?php

namespace Formation\Service\Evenement;

use Doctrine\ORM\EntityManager;
use Formation\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;

class NotificationFormationsOuvertesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NotificationFormationsOuvertesService
     */
    public function __invoke(ContainerInterface $container) : NotificationFormationsOuvertesService
    {
        /**
         * @var EntityManager $entityManager
         * @var NotificationService $notificationService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $notificationService = $container->get(NotificationService::class);

        $service = new NotificationFormationsOuvertesService();
        $service->setEntityManager($entityManager);
        $service->setNotificationService($notificationService);
        return $service;
    }
}