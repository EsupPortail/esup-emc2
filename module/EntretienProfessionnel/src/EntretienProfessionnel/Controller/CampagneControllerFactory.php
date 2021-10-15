<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
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
         * @var RenduService $renduService
         * @var MailService $mailService
         * @var ParametreService $parametreService
         */
        $campagneService = $container->get(CampagneService::class);
        $renduService = $container->get(RenduService::class);
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);

        /**
         * @var CampagneForm $campagneForm
         */
        $campagneForm = $container->get('FormElementManager')->get(CampagneForm::class);

        $controller = new CampagneController();

        $controller->setCampagneService($campagneService);
        $controller->setRenduService($renduService);
        $controller->setMailService($mailService);
        $controller->setParametreService($parametreService);

        $controller->setCampagneForm($campagneForm);

        return $controller;
    }
}