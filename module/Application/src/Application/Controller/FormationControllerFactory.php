<?php

namespace Application\Controller;

use Application\Form\Formation\FormationForm;
use Application\Form\FormationTheme\FormationThemeForm;
use Application\Service\Formation\FormationService;
use Application\Service\Formation\FormationThemeService;
use Interop\Container\ContainerInterface;

class FormationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var FormationThemeService $formationThemeService
         * @var FormationForm $formationForm
         * @var FormationThemeForm $formationThemeForm
         */
        $formationService = $container->get(FormationService::class);
        $formationThemeService = $container->get(FormationThemeService::class);
        $formationForm = $container->get('FormElementManager')->get(FormationForm::class);
        $formationThemeForm = $container->get('FormElementManager')->get(FormationThemeForm::class);

        /** @var FormationController $controller */
        $controller = new FormationController();
        $controller->setFormationService($formationService);
        $controller->setFormationThemeService($formationThemeService);
        $controller->setFormationForm($formationForm);
        $controller->setFormationThemeForm($formationThemeForm);
        return $controller;
    }

}