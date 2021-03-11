<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Agent\AgentService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceInscritControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceInscritController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var SelectionAgentForm $selectionAgentForm
         */
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);

        $controller = new FormationInstanceInscritController();
        $controller->setAgentService($agentService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setUserService($userService);
        $controller->setSelectionAgentForm($selectionAgentForm);
        return $controller;
    }
}