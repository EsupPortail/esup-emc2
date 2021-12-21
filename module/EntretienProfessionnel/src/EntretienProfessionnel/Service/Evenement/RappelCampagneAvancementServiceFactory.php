<?php

namespace EntretienProfessionnel\Service\Evenement;

use Application\Service\Structure\StructureService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;

class RappelCampagneAvancementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return RappelCampagneAvancementService
     */
    public function __invoke(ContainerInterface $container) : RappelCampagneAvancementService
    {
        /**
         * @var CampagneService $campagneService
         * @var NotificationService $notificationService
         * @var StructureService $structureService
         */
        $campagneService = $container->get(CampagneService::class);
        $notificationService = $container->get(NotificationService::class);
        $structureService = $container->get(StructureService::class);

        $service = new RappelCampagneAvancementService();
        $service->setCampagneService($campagneService);
        $service->setNotificationService($notificationService);
        $service->setStructureService($structureService);
        return $service;
    }
}