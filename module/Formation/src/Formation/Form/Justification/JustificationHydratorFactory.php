<?php

namespace Formation\Form\Justification;

use Psr\Container\ContainerInterface;

class JustificationHydratorFactory {

    public function __invoke(ContainerInterface $container) : JustificationHydrator
    {
        $hydrator = new JustificationHydrator();
        return $hydrator;
    }
}