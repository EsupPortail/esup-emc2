<?php

namespace UnicaenNote\Service\Type;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class TypeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return TypeService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entitymanager
         * @var UserService $userService
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new TypeService();
        $service->setEntityManager($entitymanager);
        $service->setUserService($userService);
        return $service;
    }

}