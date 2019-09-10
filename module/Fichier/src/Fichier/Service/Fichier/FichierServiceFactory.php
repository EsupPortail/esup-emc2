<?php

namespace Fichier\Service\Fichier;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class FichierServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FichierService $service */
        $service = new FichierService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}