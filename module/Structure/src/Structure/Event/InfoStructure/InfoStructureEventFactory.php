<?php

namespace Structure\Event\InfoStructure;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Notification\NotificationService;
use Structure\Service\Structure\StructureService;

class InfoStructureEventFactory {

    /**
     * @param ContainerInterface $container
     * @return InfoStructureEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : InfoStructureEvent
    {
        /**
         * @var EntityManager $entityManager
         * @var NotificationService $notificationService
         * @var StructureService $structureService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $notificationService = $container->get(NotificationService::class);
        $structureService = $container->get(StructureService::class);

        $event = new InfoStructureEvent();
        $event->setEntityManager($entityManager);
        $event->setNotificationService($notificationService);
        $event->setStructureService($structureService);
        return $event;
    }
}