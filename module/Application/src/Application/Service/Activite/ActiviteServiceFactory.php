<?php

namespace Application\Service\Activite;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class ActiviteServiceFactory {
    /**
     * @param ContainerInterface $container
     * @return ActiviteService
     */
    public function __invoke(ContainerInterface $container) : ActiviteService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var ActiviteService $service */
        $service = new ActiviteService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);

        return $service;
    }
}