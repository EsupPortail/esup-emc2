<?php

namespace Application\Form\Agent;

use Application\Service\Agent\AgentService;
use Zend\ServiceManager\ServiceLocatorInterface;

class MissionComplementaireHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var AgentService $agentService */
        $agentService = $parentLocator->get(AgentService::class);

        $hydrator = new MissionComplementaireHydrator();
        $hydrator->setAgentService($agentService);

        return $hydrator;
    }
}