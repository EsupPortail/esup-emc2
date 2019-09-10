<?php

namespace Application\Controller;

use Application\Form\Structure\StructureForm;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class StructureControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $roleService = $container->get(RoleService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var StructureForm $structureForm
         */
        $structureForm = $container->get('FormElementManager')->get(StructureForm::class);

        /** @var StructureController $controller */
        $controller = new StructureController();
        $controller->setRoleService($roleService);
        $controller->setStructureService($structureService);
        $controller->setUserService($userService);
        $controller->setStructureForm($structureForm);
        return $controller;
    }
}