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
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class AgentControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var RessourceRhService $ressourceService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         */
        $agentService = $container->get(AgentService::class);
        $ressourceService = $container->get(RessourceRhService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);

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
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setValidationTypeService($validationTypeService);

        $controller->setAgentForm($agentForm);
        $controller->setAgentApplicationForm($agentApplicationForm);
        $controller->setAgentCompetenceForm($agentCompetenceForm);
        $controller->setAgentFormationForm($agentFormationForm);
        $controller->setAgentMissionSpecifiqueForm($agentMissionSpecifiqueForm);

        return $controller;
    }
}