<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurForm;
use EntretienProfessionnel\Service\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CampagneConfigurationIndicateurControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationIndicateurController
    {
        /**
         * @var CampagneConfigurationIndicateurService $campagneConfigurationIndicateurService
         * @var CampagneConfigurationIndicateurForm $campagneConfigurationIndicateurForm
         */
        $campagneConfigurationIndicateurService = $container->get(CampagneConfigurationIndicateurService::class);
        $campagneConfigurationIndicateurForm = $container->get('FormElementManager')->get(CampagneConfigurationIndicateurForm::class);

        $controller = new CampagneConfigurationIndicateurController();
        $controller->setCampagneConfigurationIndicateurService($campagneConfigurationIndicateurService);
        $controller->setCampagneConfigurationIndicateurForm($campagneConfigurationIndicateurForm);
        return $controller;

    }
}
