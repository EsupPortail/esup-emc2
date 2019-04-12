<?php

namespace Autoform\Service\Validation;

use Utilisateur\Service\User\UserService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ValidationServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /** @var ValidationService $service */
        $service = new ValidationService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}