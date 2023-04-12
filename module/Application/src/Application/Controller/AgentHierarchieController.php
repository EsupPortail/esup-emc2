<?php

namespace Application\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AgentHierarchieController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;

    public function afficherAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent);
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent);

        return new ViewModel([
            'title' => "Chaîne hiérarchique de [".$agent->getDenomination()."]",
            'agent' => $agent,
            'superieurs' => $superieurs,
            'autorites' => $autorites,
        ]);
    }

}