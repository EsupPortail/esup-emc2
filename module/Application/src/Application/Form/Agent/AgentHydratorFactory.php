<?php

namespace Application\Form\Agent;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class AgentHydratorFactory  {

    public function __invoke(HydratorPluginManager $manager) {

        /**
         * @var RessourceRhService $ressourceService
         */
        $ressourceService = $manager->getServiceLocator()->get(RessourceRhService::class);

        /** @var AgentHydrator $hydrator */
        $hydrator = new AgentHydrator;
        $hydrator->setRessourceRhService($ressourceService);
        return $hydrator;
    }


}