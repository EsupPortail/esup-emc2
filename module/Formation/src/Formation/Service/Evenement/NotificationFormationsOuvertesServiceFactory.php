<?php

namespace Formation\Service\Evenement;

use Doctrine\ORM\EntityManager;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NotificationFormationsOuvertesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NotificationFormationsOuvertesService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NotificationFormationsOuvertesService
    {
        /**
         * @var EntityManager $entityManager
         * @var FormationInstanceService $formationInstanceService
         * @var NotificationService $notificationService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $notificationService = $container->get(NotificationService::class);

        $service = new NotificationFormationsOuvertesService();
        $service->setEntityManager($entityManager);
        $service->setFormationInstanceService($formationInstanceService);
        $service->setNotificationService($notificationService);
        return $service;
    }
}