<?php

namespace Element\Controller;

use Element\Form\ApplicationTheme\ApplicationThemeForm;
use Element\Service\ApplicationTheme\ApplicationThemeService;
use Interop\Container\ContainerInterface;

class ApplicationThemeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationThemeController
     */
    public function __invoke(ContainerInterface $container) : ApplicationThemeController
    {
        /**
         * @var ApplicationThemeService $applicationGroupeService
         */
        $applicationGroupeService = $container->get(ApplicationThemeService::class);

        /**
         * @var ApplicationThemeForm $applicationGroupeForm
         */
        $applicationGroupeForm = $container->get('FormElementManager')->get(ApplicationThemeForm::class);

        $controller = new ApplicationThemeController();
        $controller->setApplicationThemeService($applicationGroupeService);
        $controller->setApplicationThemeForm($applicationGroupeForm);
        return $controller;
    }

}