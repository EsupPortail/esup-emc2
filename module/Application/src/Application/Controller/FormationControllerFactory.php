<?php

namespace Application\Controller;

use Application\Form\Formation\FormationForm;
use Application\Form\FormationTheme\FormationThemeForm;
use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class FormationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var FormationForm $formationForm
         * @var FormationThemeForm $formationThemeForm
         */
        $formationService = $container->get(FormationService::class);
        $formationForm = $container->get('FormElementManager')->get(FormationForm::class);
        $formationThemeForm = $container->get('FormElementManager')->get(FormationThemeForm::class);

        /** @var FormationController $controller */
        $controller = new FormationController();
        $controller->setFormationService($formationService);
        $controller->setFormationForm($formationForm);
        $controller->setFormationThemeForm($formationThemeForm);
        return $controller;
    }

}