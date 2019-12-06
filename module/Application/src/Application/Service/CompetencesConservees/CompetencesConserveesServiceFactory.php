<?php

namespace Application\Service\CompetencesConservees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class CompetencesConserveesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetencesConserveesService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var CompetencesConserveesService $service */
        $service = new CompetencesConserveesService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}