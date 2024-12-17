<?php

namespace Agent\Service\AgentMobilite;

use Application\Service\Agent\AgentService;
use Carriere\Service\Mobilite\MobiliteService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentMobiliteServiceFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentMobiliteService
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var MobiliteService $mobiliteService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $mobiliteService = $container->get(MobiliteService::class);

        $service = new AgentMobiliteService();
        $service->setObjectManager($entityManager);
        $service->setAgentService($agentService);
        $service->setMobiliteService($mobiliteService);
        return $service;
    }
}