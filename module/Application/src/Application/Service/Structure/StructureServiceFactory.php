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
         * @var \Octopus\Service\Structure\StructureService $octopusStructureService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);
        $octopusStructureService = $serviceLocator->get(\Octopus\Service\Structure\StructureService::class);
        /**
         * @var StructureService $service
         */
        $service = new StructureService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setStructureService($octopusStructureService);

        return $service;
    }
}