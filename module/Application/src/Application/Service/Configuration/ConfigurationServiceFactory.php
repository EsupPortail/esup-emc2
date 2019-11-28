<?php

namespace Application\Service\Configuration;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class ConfigurationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ConfigurationService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ConfigurationService $service */
        $service = new ConfigurationService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}