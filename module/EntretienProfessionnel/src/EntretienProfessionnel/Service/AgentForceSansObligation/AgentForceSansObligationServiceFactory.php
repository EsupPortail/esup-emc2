<?php

namespace EntretienProfessionnel\Service\AgentForceSansObligation;

use Application\Service\Agent\AgentService;
use Doctrine\Persistence\ObjectManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentForceSansObligationServiceFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentForceSansObligationService
    {
        /**
         * @var ObjectManager $entitymanager
         * @var AgentService $agentService
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);

        $service = new AgentForceSansObligationService();
        $service->setObjectManager($entitymanager);
        $service->setAgentService($agentService);
        return $service;
    }
}