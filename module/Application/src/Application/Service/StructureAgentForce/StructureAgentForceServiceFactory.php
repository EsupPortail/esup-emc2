<?php

namespace Application\Service\StructureAgentForce;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class StructureAgentForceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return StructureAgentForceService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);

        $service = new StructureAgentForceService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        return $service;
    }
}