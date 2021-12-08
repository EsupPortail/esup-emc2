<?php

namespace EntretienProfessionnel\Service\Evenement;

use Doctrine\ORM\EntityManager;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use UnicaenEvenement\Service\Type\TypeService;

class RappelEntretienProfessionnelServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return RappelEntretienProfessionnelService
     */
    public function __invoke(ContainerInterface $container) : RappelEntretienProfessionnelService
    {
        /**
         * @var EntityManager $entityManager
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var NotificationService $notificationService
         * @var TypeService $typeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $notificationService = $container->get(NotificationService::class);
        $typeService = $container->get(TypeService::class);

        $service = new RappelEntretienProfessionnelService();

        $service->setEntityManager($entityManager);
        $service->setEntretienProfessionnelService($entretienProfessionnelService);
        $service->setNotificationService($notificationService);
        $service->setTypeService($typeService);
        return $service;
    }
}