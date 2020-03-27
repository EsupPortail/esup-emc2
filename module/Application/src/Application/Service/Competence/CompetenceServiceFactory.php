<?php

namespace Application\Service\Competence;

use Application\Service\CompetenceTheme\CompetenceThemeService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class CompetenceServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         * @var CompetenceThemeService $competenceThemeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);

        /** @var CompetenceService $service */
        $service = new CompetenceService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setCompetenceThemeService($competenceThemeService);
        return $service;
    }
}