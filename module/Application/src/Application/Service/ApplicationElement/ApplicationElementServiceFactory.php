<?php

namespace Application\Service\ApplicationElement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ApplicationElementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationElementService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new ApplicationElementService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}