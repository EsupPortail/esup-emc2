<?php

namespace Application\Form\Agent;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class AgentHydratorFactory  {

    public function __invoke(HydratorPluginManager $manager) {

        /** @var AgentHydrator $hydrator */
        $hydrator = new AgentHydrator;
        return $hydrator;
    }


}