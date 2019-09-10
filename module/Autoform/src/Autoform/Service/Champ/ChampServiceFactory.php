<?php

namespace Autoform\Service\Champ;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class ChampServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ChampService $service */
        $service = new ChampService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}