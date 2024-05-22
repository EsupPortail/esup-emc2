<?php

namespace Structure\Provider;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class IdentityProviderFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : IdentityProvider
    {
        /**
         * @var AgentService $agentService
         * @var ObservateurService $observateurService
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $observateurService = $container->get(ObservateurService::class);
        $roleService = $container->get(RoleService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $service = new IdentityProvider();
        $service->setAgentService($agentService);
        $service->setObservateurService($observateurService);
        $service->setRoleService($roleService);
        $service->setStructureService($structureService);
        $service->setUserService($userService);
        return $service;
    }
}