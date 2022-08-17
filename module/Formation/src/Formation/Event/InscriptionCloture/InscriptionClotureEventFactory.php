<?php

namespace Formation\Event\InscriptionCloture;

use Doctrine\ORM\EntityManager;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class InscriptionClotureEventFactory {

    /**
     * @param ContainerInterface $container
     * @return InscriptionClotureEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : InscriptionClotureEvent
    {
        /**
         * @var EntityManager $entityManager
         * @var FormationInstanceService $sessionService
         * @var NotificationService $notificationService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $notificationService = $container->get(NotificationService::class);
        $sessionService = $container->get(FormationInstanceService::class);

        $event = new InscriptionClotureEvent();
        $event->setEntityManager($entityManager);
        $event->setNotificationService($notificationService);
        $event->setFormationInstanceService($sessionService);
        return $event;
    }
}