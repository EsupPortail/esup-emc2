<?php

namespace Application\Controller;

use Application\Service\Agent\AgentService;
use UnicaenAuth\Service\UserContext;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class IndexControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var AgentService $agentService
         * @var RoleService $roleService
         * @var UserService $userService
         * @var UserContext $userContext
         */
        $agentService = $controllerManager->getServiceLocator()->get(AgentService::class);
        $roleService = $controllerManager->getServiceLocator()->get(RoleService::class);
        $userService = $controllerManager->getServiceLocator()->get(UserService::class);
        $userContext = $controllerManager->getServiceLocator()->get('UnicaenAuth\Service\UserContext');

        /** @var IndexController $controller */
        $controller = new IndexController();
        $controller->setAgentService($agentService);
        $controller->setUserService($userService);
        $controller->setRoleService($roleService);
        $controller->setServiceUserContext($userContext);
        return $controller;
    }

}