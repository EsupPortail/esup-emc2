<?php

namespace EntretienProfessionnel\Service\Sursis;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class SursisServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SursisService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new SursisService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}