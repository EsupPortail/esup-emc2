<?php

namespace Formation\Service\Formation;

use Doctrine\ORM\EntityManager;
use Formation\Service\FormationTheme\FormationThemeService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FormationServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var FormationThemeService $formationThemeService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $formationThemeService = $container->get(FormationThemeService::class);
        $userService = $container->get(UserService::class);

        /** @var FormationService $service */
        $service = new FormationService();
        $service->setEntityManager($entityManager);
        $service->setFormationThemeService($formationThemeService);
        $service->setUserService($userService);
        return $service;
    }
}
