<?php

namespace Application\Service\FichePoste;

use Doctrine\ORM\EntityManager;
use Utilisateur\Service\User\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class FichePosteServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /** @var FichePosteService $service */
        $service = new FichePosteService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}