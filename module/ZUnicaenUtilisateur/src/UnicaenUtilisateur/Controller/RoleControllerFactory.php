<?php

namespace UnicaenUtilisateur\Controller;

use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Form\Role\RoleForm;
use UnicaenUtilisateur\Service\Role\RoleService;

class RoleControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var RoleService $roleService
         */
        $roleService = $container->get(RoleService::class);

        /**
         * @var RoleForm $roleForm
         */
        $roleForm = $container->get('FormElementManager')->get(RoleForm::class);

        /** @var RoleController $controller */
        $controller = new RoleController();
        $controller->setRoleService($roleService);
        $controller->setRoleForm($roleForm);
        return $controller;
    }
}