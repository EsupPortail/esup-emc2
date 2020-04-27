<?php

namespace Application\Controller;

use Application\Form\Formation\FormationForm;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Service\Formation\FormationService;
use Application\Service\Formation\FormationThemeService;
use Interop\Container\ContainerInterface;

class FormationControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var FormationThemeService $formationThemeService
         */
        $formationService = $container->get(FormationService::class);
        $formationThemeService = $container->get(FormationThemeService::class);

        /**
         * @var FormationForm $formationForm
         * @var ModifierLibelleForm $modifierLibelleForm
         */
        $formationForm = $container->get('FormElementManager')->get(FormationForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);

        /** @var FormationController $controller */
        $controller = new FormationController();
        $controller->setFormationService($formationService);
        $controller->setFormationThemeService($formationThemeService);
        $controller->setFormationForm($formationForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        return $controller;
    }

}