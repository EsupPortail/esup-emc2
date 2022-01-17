<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class CampagneControllerFactory extends AbstractActionController
{
    /**
     * @param ContainerInterface $container
     * @return CampagneController
     */
    public function __invoke(ContainerInterface $container) : CampagneController
    {
        /**
         * @var CampagneService $campagneService
         * @var NotificationService $notificationService
         * @var RappelCampagneAvancementService $rappelCampagneAvancementService
         */
        $campagneService = $container->get(CampagneService::class);
        $notificationService = $container->get(NotificationService::class);
        $rappelCampagneAvancementService = $container->get(RappelCampagneAvancementService::class);

        /**
         * @var CampagneForm $campagneForm
         */
        $campagneForm = $container->get('FormElementManager')->get(CampagneForm::class);

        $controller = new CampagneController();
        $controller->setCampagneService($campagneService);
        $controller->setNotificationService($notificationService);
        $controller->setRappelCampagneAvancementService($rappelCampagneAvancementService);
        $controller->setCampagneForm($campagneForm);
        return $controller;
    }
}