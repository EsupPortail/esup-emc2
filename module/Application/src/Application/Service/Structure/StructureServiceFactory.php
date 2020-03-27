<?php

namespace Application\Service\Structure;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class StructureServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        /**
         * @var StructureService $service
         */
        $service = new StructureService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}