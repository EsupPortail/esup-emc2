<?php

namespace Application\Service\FichePoste;

use Application\Service\Agent\AgentService;
use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class FichePosteServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var FichePosteService $service */
        $service = new FichePosteService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        $service->setStructureService($structureService);
        $service->setUserService($userService);

        return $service;
    }
}