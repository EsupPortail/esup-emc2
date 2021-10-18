<?php

namespace Application\Provider;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class IdentityProviderFactory
{
    public function __invoke(ContainerInterface $container) : IdentityProvider
    {
        /**
         * @var AgentService $agentService
         * @var RoleService $roleService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);

        $service = new IdentityProvider();
        $service->setAgentService($agentService);
        $service->setRoleService($roleService);
        $service->setUserService($userService);
        return $service;
    }
}