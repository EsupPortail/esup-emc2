<?php

namespace Formation\Service\Stagiaire;

use Application\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class StagiaireServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);

        $service = new StagiaireService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        return $service;
    }
}