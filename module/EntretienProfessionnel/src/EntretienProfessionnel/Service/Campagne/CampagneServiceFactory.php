<?php

namespace EntretienProfessionnel\Service\Campagne;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class CampagneServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return CampagneService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new CampagneService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}