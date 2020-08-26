<?php

namespace Application\Controller;

use Application\Form\Activite\ActiviteForm;
use Application\Form\ModifierDescription\ModifierDescriptionForm;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\SelectionApplication\SelectionApplicationForm;
use Application\Form\SelectionCompetence\SelectionCompetenceForm;
use Application\Form\SelectionFormation\SelectionFormationForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\ActiviteDescription\ActiviteDescriptionService;
use Interop\Container\ContainerInterface;

class ActiviteControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActiviteService $activiteService
         * @var ActiviteDescriptionService $activiteDescriptionService
         */
        $activiteService = $container->get(ActiviteService::class);
        $activiteDescriptionService = $container->get(ActiviteDescriptionService::class);

        /**
         * @var ActiviteForm                $activiteForm
         * @var ModifierDescriptionForm     $modifierDescriptionForm
         * @var ModifierLibelleForm         $modifierLibelleForm
         * @var SelectionApplicationForm    $selectionApplicationForm
         * @var SelectionCompetenceForm     $selectionCompetenceForm
         * @var SelectionFormationForm      $selectionFormationForm
         */
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);
        $modifierDescriptionForm = $container->get('FormElementManager')->get(ModifierDescriptionForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);

        /** @var ActiviteController $controller */
        $controller = new ActiviteController();
        $controller->setActiviteService($activiteService);
        $controller->setActiviteDescriptionService($activiteDescriptionService);
        $controller->setActiviteForm($activiteForm);
        $controller->setModifierDescriptionForm($modifierDescriptionForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        $controller->setSelectionFormationForm($selectionFormationForm);
        return $controller;
    }
}