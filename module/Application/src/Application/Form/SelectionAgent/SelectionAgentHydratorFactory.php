<?php

namespace Application\Form\SelectionAgent;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;

class SelectionAgentHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return SelectionAgentHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         */
        $agentService     = $container->get(AgentService::class);

        /** @var SelectionAgentHydrator $hydrator */
        $hydrator = new SelectionAgentHydrator();
        $hydrator->setAgentService($agentService);
        return $hydrator;
    }
}
