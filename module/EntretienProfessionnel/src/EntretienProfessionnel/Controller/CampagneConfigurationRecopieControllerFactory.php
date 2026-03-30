<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\CampagneConfigurationRecopie\CampagneConfigurationRecopieForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\CampagneConfigurationRecopie\CampagneConfigurationRecopieService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CampagneConfigurationRecopieControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationRecopieController
    {
        /**
         * @var CampagneService $campagneService
         * @var CampagneConfigurationRecopieService $camagneConfigurationRecopieService
         */
        $campagneService = $container->get(CampagneService::class);
        $campagneConfigurationRecopieService = $container->get(CampagneConfigurationRecopieService::class);

        /**
         * @var CampagneConfigurationRecopieForm $campagneConfigurationRecopieForm
         */
        $campagneConfigurationRecopieForm = $container->get('FormElementManager')->get(CampagneConfigurationRecopieForm::class);

        $controller = new CampagneConfigurationRecopieController();
        $controller->setCampagneService($campagneService);
        $controller->setCampagneConfigurationRecopieService($campagneConfigurationRecopieService);
        $controller->setCampagneConfigurationRecopieForm($campagneConfigurationRecopieForm);
        return $controller;
    }

}
