<?php

namespace Metier\Service\Domaine;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class DomaineServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return DomaineService
     */
    public function __invoke(ContainerInterface $container) : DomaineService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        /** @var DomaineService $service */
        $service = new DomaineService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}