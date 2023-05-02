<?php

namespace Application\Form\AgentHierarchieCalcul;


use Psr\Container\ContainerInterface;

class AgentHierarchieCalculHydratorFactory
{
    public function __invoke(ContainerInterface $container) : AgentHierarchieCalculHydrator
    {
        return new AgentHierarchieCalculHydrator();
    }


}

