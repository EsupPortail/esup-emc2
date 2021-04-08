<?php

namespace UnicaenPrivilege\Controller;

use Interop\Container\ContainerInterface;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\Role\RoleService;

class PrivilegeControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var PrivilegeService $privilegeService */
        $privilegeService = $container->get(PrivilegeService::class);
        $roleService = $container->get(RoleService::class);

        /** @var PrivilegeController $controller*/
        $controller = new PrivilegeController();
        $controller->setPrivilegeService($privilegeService);
        $controller->setRoleService($roleService);
        return $controller;
    }
}