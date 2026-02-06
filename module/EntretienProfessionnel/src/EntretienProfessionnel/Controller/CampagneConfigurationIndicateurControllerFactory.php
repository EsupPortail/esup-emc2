<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\CampagneConfigurationIndicateur\CampagneConfigurationIndicateurService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenIndicateur\Service\HasIndicateurs\HasIndicateursService;

class CampagneConfigurationIndicateurControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationIndicateurController
    {
        /**
         * @var CampagneService $campagneService
         * @var CampagneConfigurationIndicateurService $campagneConfigurationIndicateurService
         * @var HasIndicateursService $hasIndicateurService
         * @var CampagneConfigurationIndicateurForm $campagneConfigurationIndicateurForm
         */
        $campagneService = $container->get(CampagneService::class);
        $campagneConfigurationIndicateurService = $container->get(CampagneConfigurationIndicateurService::class);
        $hasIndicateursService = $container->get(HasIndicateursService::class);
        $campagneConfigurationIndicateurForm = $container->get('FormElementManager')->get(CampagneConfigurationIndicateurForm::class);

        $controller = new CampagneConfigurationIndicateurController();
        $controller->setCampagneService($campagneService);
        $controller->setCampagneConfigurationIndicateurService($campagneConfigurationIndicateurService);
        $controller->setHasIndicateursService($hasIndicateursService);
        $controller->setCampagneConfigurationIndicateurForm($campagneConfigurationIndicateurForm);
        return $controller;

    }
}
