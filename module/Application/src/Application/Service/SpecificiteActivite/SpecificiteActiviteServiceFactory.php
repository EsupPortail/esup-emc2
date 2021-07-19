<?php

namespace Application\Service\SpecificiteActivite;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class SpecificiteActiviteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SpecificiteActiviteService
     */
    public function __invoke(ContainerInterface $container) : SpecificiteActiviteService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new SpecificiteActiviteService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}