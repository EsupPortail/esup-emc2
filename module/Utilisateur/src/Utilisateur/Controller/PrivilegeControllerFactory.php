<?php

namespace Utilisateur\Controller;

use Interop\Container\ContainerInterface;
use Utilisateur\Service\Privilege\PrivilegeService;
use Utilisateur\Service\Role\RoleService;

class PrivilegeControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var PrivilegeService $privilegeService
         * @var RoleService $roleService
         */
        $privilegeService   = $container->get(PrivilegeService::class);
        $roleService        = $container->get(RoleService::class);

        /** @var PrivilegeController $controller*/
        $controller = new PrivilegeController();
        $controller->setPrivilegeService($privilegeService);
        $controller->setRoleService($roleService);
        return $controller;
    }
}