<?php

namespace Application\Provider;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class IdentityProviderFactory
{
    /**
     * @param ContainerInterface $container
     * @return IdentityProvider
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : IdentityProvider
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var AgentService $agentService
         * @var RoleService $roleService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);

        $service = new IdentityProvider();
        $service->setAgentService($agentService);
        $service->setAgentAutoriteService($agentAutoriteService);
        $service->setAgentSuperieurService($agentSuperieurService);
        $service->setRoleService($roleService);
        $service->setUserService($userService);
        return $service;
    }
}