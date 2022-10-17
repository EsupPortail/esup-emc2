<?php

namespace Application\Service\Agent;

use Application\Service\AgentAffectation\AgentAffectationService;
use Application\Service\Complement\ComplementService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class AgentServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentService
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentAffectationService $agentAffectationService
         * @var ComplementService $complementService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $complementService = $container->get(ComplementService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var AgentService $service */
        $service = new AgentService();
        $service->setEntityManager($entityManager);
        $service->setAgentAffectationService($agentAffectationService);
        $service->setComplementService($complementService);
        $service->setStructureService($structureService);
        $service->setUserService($userService);

        return $service;
    }
}