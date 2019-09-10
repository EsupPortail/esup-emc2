<?php

namespace Utilisateur\Service\User;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenAuth\Service\UserContext;

class UserServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return UserService
     */
    public function __invoke(ContainerInterface $container) {

        /**
         * @var EntityManager $entityManager
         * @var UserContext $userContext
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userContext = $container->get(UserContext::class);

        /** @var UserService $service */
        $service = new UserService();
        $service->setEntityManager($entityManager);
        $service->setServiceUserContext($userContext);

        return $service;
    }
}