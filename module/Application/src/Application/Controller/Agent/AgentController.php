<?php

namespace Application\Controller\Agent;

use Application\Service\Agent\AgentServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AgentController extends AbstractActionController
{
    use AgentServiceAwareTrait;

    public function indexAction()
    {
        $agents = $this->getAgentService()->getAgents();
        return  new ViewModel([
            'agents' => $agents,
        ]);
    }

    public function afficherAction() {

        $agentId = $this->params()->fromRoute('id');
        $agent = $this->getAgentService()->getAgent($agentId);

        return new ViewModel([
            'agent' => $agent,
        ]);
    }
}