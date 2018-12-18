<?php

namespace Application\Form\Poste;

use Application\Service\Affectation\AffectationService;
use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Zend\ServiceManager\ServiceLocatorInterface;

class PosteHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /**
         * @var AgentService $agentService
         * @var AffectationService $affectationService
         * @var RessourceRhService $ressourceService
         */
        $agentService = $parentLocator->get(AgentService::class);
        $affectationService = $parentLocator->get(AffectationService::class);
        $ressourceService = $parentLocator->get(RessourceRhService::class);


        $hydrator = new PosteHydrator();
        $hydrator->setAffectationService($affectationService);
        $hydrator->setAgentService($agentService);
        $hydrator->setRessourceRhService($ressourceService);


        return $hydrator;
    }
}