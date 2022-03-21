<?php

namespace Structure\Form\AjouterGestionnaire;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;

class AjouterGestionnaireHydratorFactory {

    public function __invoke(ContainerInterface $container) : AjouterGestionnaireHydrator
    {
        /**
         * @var AgentService $agentService
         */
        $agentService = $container->get(AgentService::class);

        $hydrator = new AjouterGestionnaireHydrator();
        $hydrator->setAgentService($agentService);
        return $hydrator;
    }
}