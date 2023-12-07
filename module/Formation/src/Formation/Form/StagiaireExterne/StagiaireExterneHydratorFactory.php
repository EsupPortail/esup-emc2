<?php

namespace Formation\Form\StagiaireExterne;

use Psr\Container\ContainerInterface;

class StagiaireExterneHydratorFactory
{

    public function __invoke(ContainerInterface $container): StagiaireExterneHydrator
    {
        $hydrator = new StagiaireExterneHydrator();
        return $hydrator;
    }

}