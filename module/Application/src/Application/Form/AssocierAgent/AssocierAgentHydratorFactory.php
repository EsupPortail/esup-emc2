<?php

namespace Application\Form\AssocierAgent;

use Application\Service\Agent\AgentService;
use Zend\ServiceManager\ServiceLocatorInterface;

class AssocierAgentHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var AgentService $agentService */
        $agentService = $parentLocator->get(AgentService::class);

        $hydrator = new AssocierAgentHydrator();
        $hydrator->setAgentService($agentService);

        return $hydrator;
    }

}