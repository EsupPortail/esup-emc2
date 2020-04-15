<?php

namespace Application\Service\Metier;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class MetierServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var MetierService $service */
        $service = new MetierService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}