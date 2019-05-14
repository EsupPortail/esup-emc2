<?php

namespace  Application\Service\RessourceRh;

use Doctrine\ORM\EntityManager;
use Utilisateur\Service\User\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class RessourceRhServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RessourceRhService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /** @var RessourceRhService $service */
        $service = new RessourceRhService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}