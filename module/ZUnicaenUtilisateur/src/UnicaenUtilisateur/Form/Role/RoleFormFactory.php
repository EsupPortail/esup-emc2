<?php

namespace  UnicaenUtilisateur\Form\Role;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\Role\RoleService;

class RoleFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var RoleService $roleService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $roleService = $container->get(RoleService::class);

        /** @var RoleHydrator $roleHydrator */
        $roleHydrator = $container->get('HydratorManager')->get(RoleHydrator::class);

        /** @var RoleForm $form */
        $form = new RoleForm();
        $form->setEntityManager($entityManager);
        $form->setRoleService($roleService);
        $form->setHydrator($roleHydrator);
        return $form;
    }
}