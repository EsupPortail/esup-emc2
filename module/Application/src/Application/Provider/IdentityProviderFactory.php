<?php

namespace Application\Provider;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class IdentityProviderFactory
{
    public function __invoke(ContainerInterface $container) : IdentityProvider
    {
        /**
         * @var AgentService $agentService
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $roleService = $container->get(RoleService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $service = new IdentityProvider();
        $service->setAgentService($agentService);
        $service->setRoleService($roleService);
        $service->setStructureService($structureService);
        $service->setUserService($userService);
        return $service;
    }
}