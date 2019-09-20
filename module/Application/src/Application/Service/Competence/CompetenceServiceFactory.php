<?php

namespace Application\Service\Competence;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class CompetenceServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var CompetenceService $service */
        $service = new CompetenceService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}