<?php

namespace Autoform\Service\Champ;

use Utilisateur\Service\User\UserService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChampServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /** @var ChampService $service */
        $service = new ChampService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}