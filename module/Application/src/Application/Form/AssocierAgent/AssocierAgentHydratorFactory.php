<?php

namespace Application\Form\AssocierAgent;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;

class AssocierAgentHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AgentService $agentService */
        $agentService = $container->get(AgentService::class);

        $hydrator = new AssocierAgentHydrator();
        $hydrator->setAgentService($agentService);

        return $hydrator;
    }

}