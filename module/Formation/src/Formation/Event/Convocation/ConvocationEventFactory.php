<?php

namespace Formation\Event\Convocation;

use Doctrine\ORM\EntityManager;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenApp\Exception\RuntimeException;
use UnicaenParametre\Entity\Db\Parametre;
use UnicaenParametre\Service\Parametre\ParametreService;

class ConvocationEventFactory {

    /**
     * @param ContainerInterface $container
     * @return ConvocationEvent
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ConvocationEvent
    {
        /**
         * @var EntityManager $entityManager
         * @var FormationInstanceService $sessionService
         * @var NotificationService $notificationService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $notificationService = $container->get(NotificationService::class);
        $sessionService = $container->get(FormationInstanceService::class);

        $event = new ConvocationEvent();
        $event->setEntityManager($entityManager);
        $event->setNotificationService($notificationService);
        $event->setFormationInstanceService($sessionService);

        /** @var Parametre $deadline */
        $deadline = $container->get(ParametreService::class)->getParametreByCode(FormationParametres::TYPE, FormationParametres::AUTO_CONVOCATION);
        if ($deadline === null) throw new RuntimeException("Parametre non défini [".FormationParametres::TYPE.",".FormationParametres::AUTO_CONVOCATION."]");
        $event->setDeadline($deadline->getValeur());

        return $event;
    }
}