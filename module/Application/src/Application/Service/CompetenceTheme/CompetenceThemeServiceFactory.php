<?php

namespace Application\Service\CompetenceTheme;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class CompetenceThemeServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var CompetenceThemeService $service */
        $service = new CompetenceThemeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}