<?php

namespace Formation\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Formation\Form\Formation\FormationForm;
use Formation\Form\FormationGroupe\FormationGroupeForm;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\FormationTheme\FormationThemeService;
use Interop\Container\ContainerInterface;

class FormationControllerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         * @var FormationThemeService $formationThemeService
         */
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $formationThemeService = $container->get(FormationThemeService::class);

        /**
         * @var FormationForm $formationForm
         * @var FormationGroupeForm $formationGroupeForm
         * @var ModifierLibelleForm $modifierLibelleForm
         */
        $formationForm = $container->get('FormElementManager')->get(FormationForm::class);
        $formationGroupeForm = $container->get('FormElementManager')->get(FormationGroupeForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);

        /** @var FormationController $controller */
        $controller = new FormationController();
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setFormationThemeService($formationThemeService);
        $controller->setFormationForm($formationForm);
        $controller->setFormationGroupeForm($formationGroupeForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        return $controller;
    }

}