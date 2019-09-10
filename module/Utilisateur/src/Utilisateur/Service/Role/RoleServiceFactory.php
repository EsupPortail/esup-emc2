<?php

namespace Utilisateur\Service\Role;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

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

        /** @var RoleService $service */
        $service = new RoleService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}