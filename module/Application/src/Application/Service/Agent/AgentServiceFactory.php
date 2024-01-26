<?php

namespace Application\Service\Agent;

use Application\Service\AgentAffectation\AgentAffectationService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenParametre\Service\Parametre\ParametreService;
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
         * @var ParametreService $parametreService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $parametreService = $container->get(ParametreService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var AgentService $service */
        $service = new AgentService();
        $service->setObjectManager($entityManager);
        $service->setAgentAffectationService($agentAffectationService);
        $service->setParametreService($parametreService);
        $service->setStructureService($structureService);
        $service->setUserService($userService);

        return $service;
    }
}