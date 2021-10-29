<?php

namespace Application\Service\Agent;

use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class AgentServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentService
     */
    public function __invoke(ContainerInterface $container) : AgentService
    {
        /**
         * @var EntityManager $entityManager
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var AgentService $service */
        $service = new AgentService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);
        $service->setUserService($userService);

        return $service;
    }
}