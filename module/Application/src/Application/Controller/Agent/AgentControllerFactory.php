<?php

namespace Application\Controller\Agent;

use Application\Service\Agent\AgentService;
use Zend\Mvc\Controller\ControllerManager;

class AgentControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /** @var AgentService $agentService */
        $agentService = $controllerManager->getServiceLocator()->get(AgentService::class);

        /** @var AgentController $controller */
        $controller = new AgentController();
        $controller->setAgentService($agentService);
        return $controller;
    }
}