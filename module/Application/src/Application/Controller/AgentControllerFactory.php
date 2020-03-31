<?php

namespace Application\Controller;

use Application\Form\Agent\AgentForm;
use Application\Form\AgentApplication\AgentApplicationForm;
use Application\Form\AgentCompetence\AgentCompetenceForm;
use Application\Form\AgentFormation\AgentFormationForm;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Interop\Container\ContainerInterface;

class AgentControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var RessourceRhService $ressourceService
         */
        $agentService = $container->get(AgentService::class);
        $ressourceService = $container->get(RessourceRhService::class);

        /**
         * @var AgentForm $agentForm
         * @var AgentApplicationForm $agentApplicationForm
         * @var AgentCompetenceForm $agentCompetenceForm
         * @var AgentFormationForm $agentFormationForm
         * @var AgentMissionSpecifiqueForm $agentMissionSpecifiqueForm
         */
        $agentForm = $container->get('FormElementManager')->get(AgentForm::class);
        $agentApplicationForm = $container->get('FormElementManager')->get(AgentApplicationForm::class);
        $agentCompetenceForm = $container->get('FormElementManager')->get(AgentCompetenceForm::class);
        $agentFormationForm = $container->get('FormElementManager')->get(AgentFormationForm::class);
        $agentMissionSpecifiqueForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);

        /** @var AgentController $controller */
        $controller = new AgentController();

        $controller->setAgentService($agentService);
        $controller->setRessourceRhService($ressourceService);

        $controller->setAgentForm($agentForm);
        $controller->setAgentApplicationForm($agentApplicationForm);
        $controller->setAgentCompetenceForm($agentCompetenceForm);
        $controller->setAgentFormationForm($agentFormationForm);
        $controller->setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm);

        return $controller;
    }
}