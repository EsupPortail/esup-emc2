<?php

namespace Application\Service\ApplicationsRetirees;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class ApplicationsRetireesServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationsRetireesService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ApplicationsRetireesService $service */
        $service = new ApplicationsRetireesService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}