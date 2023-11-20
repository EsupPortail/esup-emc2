<?php

namespace Formation\Form\PlanDeFormationImportation;


use Psr\Container\ContainerInterface;

class PlanDeFormationImportationHydratorFactory
{
    public function __invoke(ContainerInterface $container) : PlanDeFormationImportationHydrator
    {
        return new PlanDeFormationImportationHydrator();
    }


}

