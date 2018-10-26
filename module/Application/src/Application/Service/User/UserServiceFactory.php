<?php

namespace Application\Service\User;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator) {

        if ($serviceLocator instanceof AbstractPluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var UserService $service */
        $service = new UserService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}