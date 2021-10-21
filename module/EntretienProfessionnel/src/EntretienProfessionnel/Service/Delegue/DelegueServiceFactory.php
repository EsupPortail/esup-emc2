<?php

namespace EntretienProfessionnel\Service\Delegue;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class DelegueServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return DelegueService
     */
    public function __invoke(ContainerInterface $container) : DelegueService
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new DelegueService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }

}