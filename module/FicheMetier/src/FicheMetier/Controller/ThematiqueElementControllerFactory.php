<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\ThematiqueElement\ThematiqueElementForm;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\ThematiqueElement\ThematiqueElementService;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ThematiqueElementControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ThematiqueElementController
    {
        /**
         * @var FicheMetierService $ficheMetierService
         * @var ThematiqueElementService $thematiqueElementService
         * @var ThematiqueTypeService $thematiqueTypeService
         */
        $ficheMetierService = $container->get(FicheMetierService::class);
        $thematiqueElementService = $container->get(ThematiqueElementService::class);
        $thematiqueTypeService = $container->get(ThematiqueTypeService::class);

        /**
         * @var ThematiqueElementForm $thematiqueElementForm
         */
        $thematiqueElementForm = $container->get('FormElementManager')->get(ThematiqueElementForm::class);

        $controller = new ThematiqueElementController();
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setThematiqueElementService($thematiqueElementService);
        $controller->setThematiqueTypeService($thematiqueTypeService);
        $controller->setThematiqueElementForm($thematiqueElementForm);
        return $controller;
    }

}