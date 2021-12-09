<?php

namespace Formation\Service\Evenement;

use Doctrine\ORM\EntityManager;
use Formation\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use UnicaenEvenement\Service\Etat\EtatService;
use UnicaenEvenement\Service\Type\TypeService;

class RappelAgentAvantFormationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return RappelAgentAvantFormationService
     */
    public function __invoke(ContainerInterface $container) : RappelAgentAvantFormationService
    {
        /**
         * @var EntityManager $entityManager
         * @var EtatService $etatService
         * @var NotificationService $notificationService
         * @var TypeService $typeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $etatService = $container->get(EtatService::class);
        $notificationService = $container->get(NotificationService::class);
        $typeService = $container->get(TypeService::class);

        $service = new RappelAgentAvantFormationService();

        $service->setEntityManager($entityManager);
        $service->setEtatEvenementService($etatService);
        $service->setNotificationService($notificationService);
        $service->setTypeService($typeService);
        return $service;
    }
}