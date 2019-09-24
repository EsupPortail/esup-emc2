<?php

namespace Application\Controller;

use Application\Form\Agent\AgentForm;
use Application\Form\AgentCompetence\AgentCompetenceForm;
use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Interop\Container\ContainerInterface;
use Octopus\Service\Individu\IndividuService;

class AgentControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var IndividuService $individuService
         * @var RessourceRhService $ressourceService
         */
        $agentService = $container->get(AgentService::class);
        $individuService = $container->get(IndividuService::class);
        $ressourceService = $container->get(RessourceRhService::class);

        /**
         * @var AgentForm $agentForm
         */
        $agentForm = $container->get('FormElementManager')->get(AgentForm::class);
        $agentCompetenceForm = $container->get('FormElementManager')->get(AgentCompetenceForm::class);

        /** @var AgentController $controller */
        $controller = new AgentController();

        $controller->setAgentService($agentService);
        $controller->setIndividuService($individuService);
        $controller->setRessourceRhService($ressourceService);

        $controller->setAgentForm($agentForm);
        $controller->setAgentCompetenceForm($agentCompetenceForm);

        return $controller;
    }
}