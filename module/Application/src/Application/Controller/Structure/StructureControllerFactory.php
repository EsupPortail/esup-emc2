<?php

namespace Application\Controller\Structure;

use Application\Service\Structure\StructureService;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class StructureControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $roleService = $manager->getServiceLocator()->get(RoleService::class);
        $structureService = $manager->getServiceLocator()->get(StructureService::class);
        $userService = $manager->getServiceLocator()->get(UserService::class);

        /** @var StructureController $controller */
        $controller = new StructureController();
        $controller->setRoleService($roleService);
        $controller->setStructureService($structureService);
        $controller->setUserService($userService);
        return $controller;
    }
}