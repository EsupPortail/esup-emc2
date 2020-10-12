<?php

namespace Application\Controller;

use Application\Form\FormationInstance\FormationInstanceForm;
use Application\Form\FormationInstanceFormateur\FormationInstanceFormateurForm;
use Application\Form\FormationInstanceFrais\FormationInstanceFraisForm;
use Application\Form\FormationJournee\FormationJourneeForm;
use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Formation\FormationService;
use Application\Service\FormationInstance\FormationInstanceFormateurService;
use Application\Service\FormationInstance\FormationInstanceFraisService;
use Application\Service\FormationInstance\FormationInstanceInscritService;
use Application\Service\FormationInstance\FormationInstanceJourneeService;
use Application\Service\FormationInstance\FormationInstancePresenceService;
use Application\Service\FormationInstance\FormationInstanceService;
use Interop\Container\ContainerInterface;
use UnicaenDocument\Service\Exporter\ExporterService;
use Zend\View\Renderer\PhpRenderer;

class FormationInstanceControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var FormationInstanceJourneeService $formationInstanceJourneeService
         * @var FormationInstancePresenceService $formationInstancePresenceService
         * @var FormationInstanceFormateurService $formationInstanceFormateurService
         * @var FormationInstanceFraisService $formationInstanceFraisService
         * @var ExporterService $exporterService
         */
        $formationService = $container->get(FormationService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);
        $formationInstancePresenceService = $container->get(FormationInstancePresenceService::class);
        $formationInstanceFormateurService = $container->get(FormationInstanceFormateurService::class);
        $formationInstanceFraisService = $container->get(FormationInstanceFraisService::class);
        $exporterService = $container->get(ExporterService::class);

        /**
         * @var FormationInstanceForm $formationInstanceForm
         * @var FormationJourneeForm $formationJourneeForm
         * @var FormationInstanceFormateurForm $formationFormateurForm
         * @var FormationInstanceFraisForm $formationFraisForm
         * @var SelectionAgentForm $selectionAgentForm
         */
        $formationInstanceForm = $container->get('FormElementManager')->get(FormationInstanceForm::class);
        $formationJourneeForm = $container->get('FormElementManager')->get(FormationJourneeForm::class);
        $formationFormateurForm = $container->get('FormElementManager')->get(FormationInstanceFormateurForm::class);
        $formationFraisForm = $container->get('FormElementManager')->get(FormationInstanceFraisForm::class);
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var FormationInstanceController $controller */
        $controller = new FormationInstanceController();
        $controller->setRenderer($renderer);
        $controller->setFormationService($formationService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
        $controller->setFormationInstancePresenceService($formationInstancePresenceService);
        $controller->setFormationInstanceFormateurService($formationInstanceFormateurService);
        $controller->setFormationInstanceFraisService($formationInstanceFraisService);
        $controller->setExporterService($exporterService);
        $controller->setFormationInstanceForm($formationInstanceForm);
        $controller->setFormationJourneeForm($formationJourneeForm);
        $controller->setFormationInstanceFormateurForm($formationFormateurForm);
        $controller->setFormationInstanceFraisForm($formationFraisForm);
        $controller->setSelectionAgentForm($selectionAgentForm);
        return $controller;
    }

}