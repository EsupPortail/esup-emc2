<?php

namespace Formation\Event\Convocation;

use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Notification\NotificationService;
use Formation\Service\Session\SessionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenApp\Exception\RuntimeException;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenParametre\Service\Parametre\ParametreService;

class ConvocationEventFactory
{

    /**
     * @param ContainerInterface $container
     * @return ConvocationEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ConvocationEvent
    {
        /**
         * @var SessionService $sessionService
         * @var NotificationService $notificationService
         */
        $notificationService = $container->get(NotificationService::class);
        $sessionService = $container->get(SessionService::class);

        $event = new ConvocationEvent();
        $event->setNotificationService($notificationService);
        $event->setSessionService($sessionService);

        /** @var Parametre $deadline */
        $deadline = $container->get(ParametreService::class)->getParametreByCode(FormationParametres::TYPE, FormationParametres::AUTO_CONVOCATION);
        if ($deadline === null) throw new RuntimeException("Parametre non défini [" . FormationParametres::TYPE . "," . FormationParametres::AUTO_CONVOCATION . "]");
        $event->setDeadline($deadline->getValeur());

        return $event;
    }
}