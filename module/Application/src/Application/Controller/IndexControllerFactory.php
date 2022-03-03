<?php

namespace Application\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\Structure\StructureService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;
use UnicaenAuthentification\Service\UserContext;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class IndexControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var CampagneService $campagneService
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UserService $userService
         * @var UserContext $userContext
         */
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(CampagneService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);
        $userContext = $container->get(UserContext::class);
        $structureService = $container->get(StructureService::class);

        /** @var IndexController $controller */
        $controller = new IndexController();
        $controller->setAgentService($agentService);
        $controller->setCampagneService($campagneService);
        $controller->setUserService($userService);
        $controller->setRoleService($roleService);
        $controller->setServiceUserContext($userContext);
        $controller->setStructureService($structureService);
        return $controller;
    }

}