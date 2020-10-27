<?php

namespace Formation\Controller;

use Formation\Form\FormationTheme\FormationThemeForm;
use Formation\Service\FormationTheme\FormationThemeService;
use Interop\Container\ContainerInterface;

class FormationThemeControllerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationThemeService $formationThemeService
         */
        $formationThemeService = $container->get(FormationThemeService::class);

        /**
         * @var FormationThemeForm $formationThemeForm
         */
        $formationThemeForm = $container->get('FormElementManager')->get(FormationThemeForm::class);

        $controller = new FormationThemeController();
        $controller->setFormationThemeService($formationThemeService);
        $controller->setFormationThemeForm($formationThemeForm);
        return $controller;
    }


}