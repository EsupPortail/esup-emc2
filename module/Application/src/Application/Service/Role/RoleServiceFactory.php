<?php

namespace Application\Service\Role;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class RoleServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RoleService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator) {

        if ($serviceLocator instanceof AbstractPluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

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