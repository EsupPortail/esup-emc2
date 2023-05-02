<?php

namespace Application\Service\AgentAutorite;

use Application\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentAutoriteServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentAutoriteService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentAutoriteService
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);

        $service = new AgentAutoriteService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        return $service;
    }
}