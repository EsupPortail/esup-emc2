<?php

namespace EntretienProfessionnel\Service\AgentForceSansObligation;

use Application\Service\Agent\AgentService;
use Doctrine\Persistence\ObjectManager;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentForceSansObligationServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentForceSansObligationService
    {
        /**
         * @var ObjectManager $entitymanager
         * @var AgentService $agentService
         * @var CampagneService $campagneService
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(CampagneService::class);

        $service = new AgentForceSansObligationService();
        $service->setObjectManager($entitymanager);
        $service->setAgentService($agentService);
        $service->setCampagneService($campagneService);
        return $service;
    }
}