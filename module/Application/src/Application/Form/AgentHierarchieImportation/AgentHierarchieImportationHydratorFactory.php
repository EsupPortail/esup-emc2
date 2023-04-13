<?php

namespace Application\Form\AgentHierarchieImportation;


use Psr\Container\ContainerInterface;

class AgentHierarchieImportationHydratorFactory
{
    public function __invoke(ContainerInterface $container) : AgentHierarchieImportationHydrator
    {
        return new AgentHierarchieImportationHydrator();
    }


}

