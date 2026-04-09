<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieForm;
use EntretienProfessionnel\Service\CampagneConfigurationPresaisie\CampagneConfigurationPresaisieService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CampagneConfigurationPresaisieControllerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationPresaisieController
    {
        /**
         * @var CampagneConfigurationPresaisieService $campagnePresaisieService
         * @var CampagneConfigurationPresaisieForm $campagnePresaisieForm
         */
        $campagnePresaisieService = $container->get(CampagneConfigurationPresaisieService::class);
        $campagnePresaisieForm = $container->get('FormElementManager')->get(CampagneConfigurationPresaisieForm::class);

        $controller = new CampagneConfigurationPresaisieController();
        $controller->setCampagneConfigurationPresaisieService($campagnePresaisieService);
        $controller->setCampagneConfigurationPresaisieForm($campagnePresaisieForm);
        return $controller;
    }
}
