<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;
use Zend\Mvc\Controller\AbstractActionController;

class CampagneControllerFactory extends AbstractActionController {

    /**
     * @param ContainerInterface $container
     * @return CampagneController
     */
    public function __invoke(ContainerInterface $container) : CampagneController
    {
        /**
         * @var CampagneService $campagneService
         * @var NotificationService $notificationService
         */
        $campagneService = $container->get(CampagneService::class);
        $notificationService = $container->get(NotificationService::class);

        /**
         * @var CampagneForm $campagneForm
         */
        $campagneForm = $container->get('FormElementManager')->get(CampagneForm::class);

        $controller = new CampagneController();
        $controller->setCampagneService($campagneService);
        $controller->setNotificationService($notificationService);
        $controller->setCampagneForm($campagneForm);
        return $controller;
    }
}