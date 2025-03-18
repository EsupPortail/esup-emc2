<?php

namespace Application\Form\SelectionAgent;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionAgentHydratorFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionAgentHydrator
    {
        /**
         * @var AgentService $agentService
         */
        $agentService     = $container->get(AgentService::class);

        $hydrator = new SelectionAgentHydrator();
        $hydrator->setAgentService($agentService);
        return $hydrator;
    }
}
