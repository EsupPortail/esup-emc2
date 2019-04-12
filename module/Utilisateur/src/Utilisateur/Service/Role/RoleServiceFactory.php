<?php

namespace Utilisateur\Service\Role;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class RoleServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RoleService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator) {

        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var RoleService $service */
        $service = new RoleService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}