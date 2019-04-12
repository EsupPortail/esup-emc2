<?php

namespace Utilisateur\Controller;

use Utilisateur\Service\Privilege\PrivilegeService;
use Utilisateur\Service\Role\RoleService;
use Zend\Mvc\Controller\ControllerManager;

class PrivilegeControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var PrivilegeService $privilegeService
         * @var RoleService $roleService
         */
        $privilegeService   = $manager->getServiceLocator()->get(PrivilegeService::class);
        $roleService        = $manager->getServiceLocator()->get(RoleService::class);

        /** @var PrivilegeController $controller*/
        $controller = new PrivilegeController();
        $controller->setPrivilegeService($privilegeService);
        $controller->setRoleService($roleService);
        return $controller;
    }
}