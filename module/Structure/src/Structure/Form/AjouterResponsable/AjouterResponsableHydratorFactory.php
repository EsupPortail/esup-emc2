<?php

namespace Structure\Form\AjouterResponsable;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;

class AjouterResponsableHydratorFactory {

    public function __invoke(ContainerInterface $container) : AjouterResponsableHydrator
    {
        /**
         * @var AgentService $agentService
         */
        $agentService = $container->get(AgentService::class);

        $hydrator = new AjouterResponsableHydrator();
        $hydrator->setAgentService($agentService);
        return $hydrator;
    }
}