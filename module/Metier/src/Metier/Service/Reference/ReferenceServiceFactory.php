<?php

namespace Metier\Service\Reference;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ReferenceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferenceService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ReferenceService $service */
        $service = new ReferenceService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}