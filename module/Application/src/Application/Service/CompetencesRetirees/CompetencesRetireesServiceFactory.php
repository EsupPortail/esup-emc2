<?php

namespace Application\Service\CompetencesRetirees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class CompetencesRetireesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetencesRetireesService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var CompetencesRetireesService $service */
        $service = new CompetencesRetireesService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}