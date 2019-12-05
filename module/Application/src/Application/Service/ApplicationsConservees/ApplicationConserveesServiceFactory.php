<?php

namespace Application\Service\ApplicationsConservees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class ApplicationConserveesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationsConserveesService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ApplicationsConserveesService $service */
        $service = new ApplicationsConserveesService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}