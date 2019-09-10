<?php

namespace Application\Form\Agent;

use Interop\Container\ContainerInterface;

class AgentHydratorFactory  {

    public function __invoke(ContainerInterface $container) {

        /** @var AgentHydrator $hydrator */
        $hydrator = new AgentHydrator;
        return $hydrator;
    }


}