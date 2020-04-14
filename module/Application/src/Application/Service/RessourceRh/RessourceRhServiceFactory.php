<?php

namespace  Application\Service\RessourceRh;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class RessourceRhServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return RessourceRhService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var RessourceRhService $service */
        $service = new RessourceRhService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}