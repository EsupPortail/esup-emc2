<?php

namespace Application\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\Validation\ValidationDemandeService;
use Interop\Container\ContainerInterface;
use UnicaenAuth\Service\UserContext;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class IndexControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var RoleService $roleService
         * @var ValidationDemandeService $validationDemandeService
         * @var UserService $userService
         * @var UserContext $userContext
         */
        $agentService = $container->get(AgentService::class);
        $roleService = $container->get(RoleService::class);
        $validationDemandeService = $container->get(ValidationDemandeService::class);
        $userService = $container->get(UserService::class);
        $userContext = $container->get(UserContext::class);

        /** @var IndexController $controller */
        $controller = new IndexController();
        $controller->setAgentService($agentService);
        $controller->setUserService($userService);
        $controller->setRoleService($roleService);
        $controller->setValidationDemandeService($validationDemandeService);
        $controller->setServiceUserContext($userContext);
        return $controller;
    }

}