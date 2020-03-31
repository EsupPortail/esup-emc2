<?php

namespace UnicaenValidation\Service\ValidationInstance;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ValidationInstanceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ValidationInstanceService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ValidationInstanceService $service */
        $service = new ValidationInstanceService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}