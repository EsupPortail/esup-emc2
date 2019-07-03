<?php

namespace Application\Service\Structure;

use Utilisateur\Service\User\UserService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class StructureServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);
        /**
         * @var StructureService $service
         */
        $service = new StructureService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}