<?php

namespace Application\Service\CompetenceType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class CompetenceTypeServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var CompetenceTypeService $service */
        $service = new CompetenceTypeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}