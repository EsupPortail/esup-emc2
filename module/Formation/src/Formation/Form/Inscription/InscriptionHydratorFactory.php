<?php

namespace Formation\Form\Inscription;

use Application\Service\Agent\AgentService;
use Formation\Service\Session\SessionService;
use Formation\Service\StagiaireExterne\StagiaireExterneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class InscriptionHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return InscriptionHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionHydrator
    {
        /**
         * @var AgentService $agentService
         * @var SessionService $sessionService
         * @var StagiaireExterneService $stagiaireService
         */
        $agentService = $container->get(AgentService::class);
        $sessionService = $container->get(SessionService::class);
        $stagiaireService = $container->get(StagiaireExterneService::class);

        $hydrator = new InscriptionHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setSessionService($sessionService);
        $hydrator->setStagiaireExterneService($stagiaireService);
        return $hydrator;
    }
}