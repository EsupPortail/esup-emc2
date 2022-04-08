<?php

namespace Application\Form\Rifseep;

use Interop\Container\ContainerInterface;

class RifseepHydratorFactory {

    public function __invoke(ContainerInterface $container) : RifseepHydrator
    {
        $hydrator = new RifseepHydrator();
        return $hydrator;
    }
}