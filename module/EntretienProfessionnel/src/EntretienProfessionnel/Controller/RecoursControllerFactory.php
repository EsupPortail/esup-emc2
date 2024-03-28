<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Recours\RecoursForm;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use EntretienProfessionnel\Service\Recours\RecoursService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RecoursControllerFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RecoursController
    {
        /**
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var NotificationService $notificationService
         * @var RecoursService $recoursService
         * @var RecoursForm $recoursForm
         */
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $notificationService = $container->get(NotificationService::class);
        $recoursService = $container->get(RecoursService::class);
        $recoursForm = $container->get('FormElementManager')->get(RecoursForm::class);

        $controller = new RecoursController();
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setNotificationService($notificationService);
        $controller->setRecoursService($recoursService);
        $controller->setRecoursForm($recoursForm);
        return $controller;
    }
}