<?php

namespace UnicaenValidation\Service\ValidationType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ValidationTypeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ValidationTypeService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ValidationTypeService $service */
        $service = new ValidationTypeService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}