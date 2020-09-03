<?php

namespace Application\Controller;

use Application\Form\FormationJournee\FormationJourneeForm;
use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Formation\FormationService;
use Application\Service\FormationInstance\FormationInstanceInscritService;
use Application\Service\FormationInstance\FormationInstanceJourneeService;
use Application\Service\FormationInstance\FormationInstanceService;
use Interop\Container\ContainerInterface;

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
         */
        $formationService = $container->get(FormationService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);

        /**
         * @var FormationJourneeForm $formationJourneeForm
         * @var SelectionAgentForm $selectionAgentForm;
         */
        $formationJourneeForm = $container->get('FormElementManager')->get(FormationJourneeForm::class);
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);

        /** @var FormationInstanceController $controller */
        $controller = new FormationInstanceController();
        $controller->setFormationService($formationService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
        $controller->setFormationJourneeForm($formationJourneeForm);
        $controller->setSelectionAgentForm($selectionAgentForm);
        return $controller;
    }

}