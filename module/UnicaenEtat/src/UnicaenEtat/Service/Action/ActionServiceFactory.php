<?php

namespace UnicaenEtat\Service\Action;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ActionServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ActionService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new ActionService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}