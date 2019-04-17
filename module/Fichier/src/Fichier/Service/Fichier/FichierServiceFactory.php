<?php

namespace Fichier\Service\Fichier;

use Doctrine\ORM\EntityManager;
use Utilisateur\Service\User\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class FichierServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /** @var FichierService $service */
        $service = $serviceLocator->get(FichierService::class);
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}