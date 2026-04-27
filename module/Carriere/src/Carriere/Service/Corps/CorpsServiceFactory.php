<?php

namespace Carriere\Service\Corps;

use Agent\Service\Agent\AgentService;
use Agent\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\SqlHelper\SqlHelperService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class CorpsServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return CorpsService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CorpsService
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var AgentSuperieurService $agentSuperieurService
         * @var SqlHelperService $sqlHelperResult
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $sqlHelperResult = $container->get(SqlHelperService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);


        $service = new CorpsService();
        $service->setObjectManager($entityManager);
        $service->setAgentService($agentService);
        $service->setAgentSuperieurService($agentSuperieurService);
        $service->setSqlHelperService($sqlHelperResult);
        $service->setStructureService($structureService);
        $service->setUserService($userService);
        return $service;
    }
}
