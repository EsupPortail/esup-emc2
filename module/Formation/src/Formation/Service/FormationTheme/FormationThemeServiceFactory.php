<?php

namespace Formation\Service\FormationTheme;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationThemeServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FormationThemeService $service */
        $service = new FormationThemeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}
