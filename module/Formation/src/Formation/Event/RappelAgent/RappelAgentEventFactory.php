<?php

namespace Formation\Event\RappelAgent;

use Doctrine\ORM\EntityManager;
use Formation\Service\Notification\NotificationService;
use Formation\Service\Session\SessionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEvenement\Service\Etat\EtatService;
use UnicaenEvenement\Service\Type\TypeService;
use UnicaenParametre\Service\Parametre\ParametreService;

class RappelAgentEventFactory
{

    /**
     * @param ContainerInterface $container
     * @return RappelAgentEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RappelAgentEvent
    {
        /**
         * @var EntityManager $entityManager
         * @var EtatService $etatService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var SessionService $sessionService
         * @var TypeService $typeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $etatService = $container->get(EtatService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $sessionService = $container->get(SessionService::class);
        $typeService = $container->get(TypeService::class);

        $event = new RappelAgentEvent();
        $event->setEntityManager($entityManager);
        $event->setObjectManager($entityManager);
        $event->setEtatEvenementService($etatService);
        $event->setNotificationService($notificationService);
        $event->setParametreService($parametreService);
        $event->setSessionService($sessionService);
        $event->setTypeService($typeService);

        return $event;
    }
}