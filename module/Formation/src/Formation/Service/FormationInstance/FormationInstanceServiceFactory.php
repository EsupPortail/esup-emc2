<?php

namespace Formation\Service\FormationInstance;

use Doctrine\ORM\EntityManager;
use Formation\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenParametre\Service\Parametre\ParametreService;

class FormationInstanceServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormationInstanceService
    {
        /**
         * @var EntityManager $entityManager
         * @var EtatService $etatService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $etatService = $container->get(EtatService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);

        /**
         * @var FormationInstanceService $service
         */
        $service = new FormationInstanceService();
        $service->setEntityManager($entityManager);
        $service->setEtatService($etatService);
        $service->setNotificationService($notificationService);
        $service->setParametreService($parametreService);
        return $service;
    }
}