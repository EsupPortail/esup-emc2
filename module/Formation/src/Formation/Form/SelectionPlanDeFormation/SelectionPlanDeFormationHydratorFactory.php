<?php

namespace Formation\Form\SelectionPlanDeFormation;

use Interop\Container\ContainerInterface;

class SelectionPlanDeFormationHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return SelectionPlanDeFormationHydrator
     */
    public function __invoke(ContainerInterface $container): SelectionPlanDeFormationHydrator
    {
        $hydrator = new SelectionPlanDeFormationHydrator();
        return $hydrator;
    }
}