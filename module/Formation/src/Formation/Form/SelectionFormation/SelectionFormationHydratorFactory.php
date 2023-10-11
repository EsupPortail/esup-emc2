<?php

namespace Formation\Form\SelectionFormation;

use Interop\Container\ContainerInterface;

class SelectionFormationHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return SelectionFormationHydrator
     */
    public function __invoke(ContainerInterface $container): SelectionFormationHydrator
    {
        $hydrator = new SelectionFormationHydrator();
        return $hydrator;
    }
}