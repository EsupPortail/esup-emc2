<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use Zend\Mvc\Controller\AbstractActionController;

class CampagneControllerFactory extends AbstractActionController {

    /**
     * @param ContainerInterface $container
     * @return CampagneController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CampagneService $campagneService
         * @var MailingService $mailingService
         */
        $campagneService = $container->get(CampagneService::class);
        $mailingService = $container->get(MailingService::class);

        /**
         * @var CampagneForm $campagneForm
         */
        $campagneForm = $container->get('FormElementManager')->get(CampagneForm::class);

        $controller = new CampagneController();

        $controller->setCampagneService($campagneService);
        $controller->setMailingService($mailingService);

        $controller->setCampagneForm($campagneForm);

        return $controller;
    }
}