<?php

namespace UnicaenUtilisateur\Form\Role;

use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\Role\RoleService;

class RoleHydratorFactory {

    public function __invoke(ContainerInterface $container) {

        /**
         * @var RoleService $roleService
         */
        $roleService = $container->get(RoleService::class);

        /** @var RoleHydrator $hydrator */
        $hydrator = new RoleHydrator();
        $hydrator->setRoleService($roleService);
        return $hydrator;
    }
}