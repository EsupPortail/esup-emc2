<?php

namespace Formation\Form\Abonnement;

use Application\Service\Agent\AgentService;
use Formation\Service\Formation\FormationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AbonnementHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return AbonnementHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AbonnementHydrator
    {
        /**
         * @see AgentService $agentService
         * @see FormationService $formationService
         */
        $agentService = $container->get(AgentService::class);
        $formationService = $container->get(FormationService::class);

        $hydrator = new AbonnementHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}