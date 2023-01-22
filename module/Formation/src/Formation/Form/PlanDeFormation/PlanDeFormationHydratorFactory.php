<?php

namespace Formation\Form\PlanDeFormation;

use Psr\Container\ContainerInterface;

class PlanDeFormationHydratorFactory {

    public function __invoke(ContainerInterface $container) : PlanDeFormationHydrator
    {
        $hydrator = new PlanDeFormationHydrator();
        return $hydrator;
    }
}