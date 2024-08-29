<?php

namespace Formation\Event\NotificationNouvellesSessions;

use Doctrine\ORM\EntityManager;
use Formation\Event\DemandeRetour\DemandeRetourEvent;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Notification\NotificationService;
use Formation\Service\Session\SessionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenApp\Exception\RuntimeException;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenParametre\Service\Parametre\ParametreService;

class NotificationNouvellesSessionsEventFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): NotificationNouvellesSessionsEvent
    {
        /**
         * @var EntityManager $entityManager
         * @var NotificationService $notificationService
         * @var SessionService $sessionService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $notificationService = $container->get(NotificationService::class);
        $sessionService = $container->get(SessionService::class);

        $event = new NotificationNouvellesSessionsEvent();
        $event->setEntityManager($entityManager);
        $event->setObjectManager($entityManager);
        $event->setSessionService($sessionService);
        $event->setNotificationService($notificationService);
        return $event;
    }
}