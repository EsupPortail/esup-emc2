<?php

namespace Application\Form\AgentHierarchieSaisie;

use Psr\Container\ContainerInterface;

class AgentHierarchieSaisieHydratorFactory
{
    public function __invoke(ContainerInterface $container) : AgentHierarchieSaisieHydrator
    {
        $hydrator = new AgentHierarchieSaisieHydrator();
        return $hydrator;
    }
}