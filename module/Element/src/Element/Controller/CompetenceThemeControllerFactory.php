<?php

namespace Element\Controller;

use Element\Form\CompetenceTheme\CompetenceThemeForm;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceThemeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceThemeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceThemeController
    {
        /**
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceThemeForm $competenceThemeForm
         */
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceThemeForm = $container->get('FormElementManager')->get(CompetenceThemeForm::class);

        $controller = new CompetenceThemeController();
        $controller->setCompetenceThemeService($competenceThemeService);
        $controller->setCompetenceThemeForm($competenceThemeForm);
        return $controller;
    }
}