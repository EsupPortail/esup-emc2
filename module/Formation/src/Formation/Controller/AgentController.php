<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use UserServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $params = $this->params()->fromQuery();
        $agents = $this->getAgentService()->getAgentsWithFiltre($params);

        return new ViewModel([
            'agents' => $agents,
            'params' => $params,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        return new ViewModel([
            'agent' => $agent,
        ]);
    }

    public function mesAgentsAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $agents = []; //$this->getAgentService()->getAgentsByResponsabilite($user, $role);
        return new ViewModel([
            'user' => $user,
            'role' => $role,
            'agents' => $agents,
        ]);
    }
}