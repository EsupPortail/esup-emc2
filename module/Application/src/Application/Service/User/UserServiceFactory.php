<?php

namespace Application\Service\User;

use Doctrine\ORM\EntityManager;
use UnicaenAuth\Service\UserContext;
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
         * @var UserContext $userContext
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userContext = $serviceLocator->get('UnicaenAuth\Service\UserContext');

        /** @var UserService $service */
        $service = new UserService();
        $service->setEntityManager($entityManager);
        $service->setServiceUserContext($userContext);

        return $service;
    }
}