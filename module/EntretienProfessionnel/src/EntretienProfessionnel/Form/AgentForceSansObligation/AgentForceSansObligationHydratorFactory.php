<?php

namespace EntretienProfessionnel\Form\AgentForceSansObligation;

use Application\Service\Agent\AgentService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentForceSansObligationHydratorFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentForceSansObligationHydrator
    {
        /**
         * @var AgentService $agentService
         * @var CampagneService $campagneService
         */
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(CampagneService::class);

        $hydrator = new AgentForceSansObligationHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setCampagneService($campagneService);
        return $hydrator;
    }
}