<?php

namespace Application\Service\ActiviteDescription;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class ActiviteDescriptionServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ActiviteDescriptionService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ActiviteDescriptionService $service */
        $service = new ActiviteDescriptionService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}