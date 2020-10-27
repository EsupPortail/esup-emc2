<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentForm;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Interop\Container\ContainerInterface;

class FormationInstanceInscritControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceInscritController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);

        /**
         * @var SelectionAgentForm $selectionAgentForm
         */
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);

        $controller = new FormationInstanceInscritController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setSelectionAgentForm($selectionAgentForm);
        return $controller;
    }
}