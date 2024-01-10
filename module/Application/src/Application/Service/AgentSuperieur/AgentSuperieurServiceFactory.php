<?php

namespace Application\Service\AgentSuperieur;

use Application\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentSuperieurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentSuperieurService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentSuperieurService
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);

        $service = new AgentSuperieurService();
        $service->setObjectManager($entityManager);
        $service->setAgentService($agentService);
        return $service;
    }
}