<?php

namespace FicheMetier\Controller;

use FicheMetier\Form\ThematiqueType\ThematiqueElementForm;
use FicheMetier\Form\ThematiqueType\ThematiqueTypeForm;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ThematiqueTypeControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ThematiqueTypeController
    {
        /**
         * @var ThematiqueTypeService $thematiqueTypeService
         * @var ThematiqueTypeForm $thematiqueTypeForm
         */
        $thematiqueTypeService = $container->get(ThematiqueTypeService::class);
        $thematiqueTypeForm = $container->get('FormElementManager')->get(ThematiqueTypeForm::class);

        $controller = new ThematiqueTypeController();
        $controller->setThematiqueTypeService($thematiqueTypeService);
        $controller->setThematiqueTypeForm($thematiqueTypeForm);
        return $controller;
    }
}