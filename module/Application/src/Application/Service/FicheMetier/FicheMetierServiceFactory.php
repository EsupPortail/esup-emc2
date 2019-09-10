<?php

namespace Application\Service\FicheMetier;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\User\UserService;

class FicheMetierServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var FicheMetierService $service */
        $service = new FicheMetierService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}