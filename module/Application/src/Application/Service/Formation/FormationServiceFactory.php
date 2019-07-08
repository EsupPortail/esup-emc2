<?php

namespace Application\Service\Formation;

use Doctrine\ORM\EntityManager;
use Utilisateur\Service\User\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class FormationServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /** @var FormationService $service */
        $service = new FormationService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}