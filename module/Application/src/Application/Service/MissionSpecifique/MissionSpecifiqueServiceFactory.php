<?php

namespace Application\Service\MissionSpecifique;

use Doctrine\ORM\EntityManager;
use Utilisateur\Service\User\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class MissionSpecifiqueServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);


        /** @var MissionSpecifiqueService $service */
        $service = new MissionSpecifiqueService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}