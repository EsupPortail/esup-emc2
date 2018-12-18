<?php

namespace Application\Form\Agent;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\ServiceManager\ServiceLocatorInterface;

class AgentHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /** @var RessourceRhService $ressourceService */
        $ressourceService = $parentLocator->get(RessourceRhService::class);

        $hydrator = new AgentHydrator();
        $hydrator->setRessourceRhService($ressourceService);

        return $hydrator;
    }
}