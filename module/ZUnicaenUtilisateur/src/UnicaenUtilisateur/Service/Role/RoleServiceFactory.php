<?php

namespace UnicaenUtilisateur\Service\Role;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Entity\Db\Role;

class RoleServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return RoleService
     */
    public function __invoke(ContainerInterface $container) {

        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $allConfig = $container->get('Config');

        /** @var RoleService $service */
        $service = new RoleService();
        $service->setEntityManager($entityManager);
        $service->setRoleEntityClass($allConfig['unicaen-auth']['role_entity_class'] ?? Role::class);

        return $service;
    }
}