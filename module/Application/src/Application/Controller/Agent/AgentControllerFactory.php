<?php

namespace Application\Controller\Agent;

use Application\Form\Agent\AgentForm;
use Application\Service\Agent\AgentService;
use Zend\Mvc\Controller\ControllerManager;

class AgentControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /** @var AgentService $agentService */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);

        /** @var AgentForm $agentForm */
        $agentForm = $manager->getServiceLocator()->get('FormElementManager')->get(AgentForm::class);

        /** @var AgentController $controller */
        $controller = new AgentController();
        $controller->setAgentService($agentService);
        $controller->setAgentForm($agentForm);
        return $controller;
    }
}