<?php

namespace Application\Controller;

use Application\Form\Activite\ActiviteForm;
use Application\Form\HasDescription\HasDescriptionForm;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Application\Form\SelectionApplication\SelectionApplicationForm;
use Application\Form\SelectionCompetence\SelectionCompetenceForm;
use Application\Service\HasApplicationCollection\HasApplicationCollectionService;
use Application\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Application\Service\NiveauEnveloppe\NiveauEnveloppeService;
use Formation\Form\SelectionFormation\SelectionFormationForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\ActiviteDescription\ActiviteDescriptionService;
use Formation\Service\HasFormationCollection\HasFormationCollectionService;
use Interop\Container\ContainerInterface;

class ActiviteControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActiviteService $activiteService
         * @var ActiviteDescriptionService $activiteDescriptionService
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var HasCompetenceCollectionService $hasCompetenceCollectionService
         * @var HasFormationCollectionService $hasFormationCollectionService
         * @var NiveauEnveloppeService $niveauEnveloppeService
         */
        $activiteService = $container->get(ActiviteService::class);
        $activiteDescriptionService = $container->get(ActiviteDescriptionService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $hasCompetenceCollectionService = $container->get(HasCompetenceCollectionService::class);
        $hasFormationCollectionService = $container->get(HasFormationCollectionService::class);
        $niveauEnveloppeService = $container->get(NiveauEnveloppeService::class);

        /**
         * @var ActiviteForm                $activiteForm
         * @var HasDescriptionForm          $hasDescriptionForm
         * @var ModifierLibelleForm         $modifierLibelleForm
         * @var NiveauEnveloppeForm         $niveauEnveloppeForm
         * @var SelectionApplicationForm    $selectionApplicationForm
         * @var SelectionCompetenceForm     $selectionCompetenceForm
         * @var SelectionFormationForm      $selectionFormationForm
         */
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);
        $hasDescriptionForm = $container->get('FormElementManager')->get(HasDescriptionForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $niveauEnveloppeForm = $container->get('FormElementManager')->get(NiveauEnveloppeForm::class);
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);

        /** @var ActiviteController $controller */
        $controller = new ActiviteController();
        $controller->setActiviteService($activiteService);
        $controller->setActiviteDescriptionService($activiteDescriptionService);
        $controller->setHasApplicationCollectionService($hasApplicationCollectionService);
        $controller->setHasCompetenceCollectionService($hasCompetenceCollectionService);
        $controller->setHasFormationCollectionService($hasFormationCollectionService);
        $controller->setNiveauEnveloppeService($niveauEnveloppeService);
        $controller->setActiviteForm($activiteForm);
        $controller->setHasDescriptionForm($hasDescriptionForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setNiveauEnveloppeForm($niveauEnveloppeForm);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        $controller->setSelectionFormationForm($selectionFormationForm);
        return $controller;
    }
}