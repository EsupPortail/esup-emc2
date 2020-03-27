<?php

namespace Application\Service\Activite;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class ActiviteServiceFactory {
    /**
     * @param ContainerInterface $serviceLocator
     * @return ActiviteService
     */
    public function __invoke(ContainerInterface $serviceLocator) {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $userService = $serviceLocator->get(UserService::class);

        /** @var ActiviteService $service */
        $service = new ActiviteService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}