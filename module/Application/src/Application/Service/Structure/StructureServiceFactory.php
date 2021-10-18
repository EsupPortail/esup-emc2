<?php

namespace Application\Service\Structure;

use Application\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class StructureServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        //$agentService = $container->get(AgentService::class);
        $userService = $container->get(UserService::class);
        /**
         * @var StructureService $service
         */
        $service = new StructureService();
        $service->setEntityManager($entityManager);
        //$service->setAgentService($agentService);
        $service->setUserService($userService);

        return $service;
    }
}