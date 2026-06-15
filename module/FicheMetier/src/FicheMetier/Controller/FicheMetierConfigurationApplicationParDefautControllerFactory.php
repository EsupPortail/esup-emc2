<?php

namespace FicheMetier\Controller;

use Element\Form\SelectionApplication\SelectionApplicationForm;
use Element\Service\Application\ApplicationService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\FicheMetierConfigurationApplicationParDefaut\FicheMetierConfigurationApplicationParDefautService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class FicheMetierConfigurationApplicationParDefautControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FicheMetierConfigurationApplicationParDefautController
    {
        /**
         * @var ApplicationService $applicationService
         * @var FicheMetierService $ficheMetierService $ficheMetierService
         * @var FicheMetierConfigurationApplicationParDefautService $ficheMetierConfigurationApplicationParDefautService
         * @var ParametreService $parametreService
         */
        $applicationService = $container->get(ApplicationService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $ficheMetierConfigurationApplicationParDefautService = $container->get(FicheMetierConfigurationApplicationParDefautService::class);
        $parametreService = $container->get(ParametreService::class);

        /**
         * @var SelectionApplicationForm $selectionApplicationForm
         */
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);

        $controller = new FicheMetierConfigurationApplicationParDefautController();
        $controller->setApplicationService($applicationService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFicheMetierConfigurationApplicationParDefautService($ficheMetierConfigurationApplicationParDefautService);
        $controller->setParametreService($parametreService);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        return $controller;

    }
}
