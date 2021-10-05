<?php

namespace Application\Form\HasPeriode;

use Interop\Container\ContainerInterface;

class HasPeriodeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return HasPeriodeHydrator
     */
    public function __invoke(ContainerInterface $container) : HasPeriodeHydrator
    {
        $hydrator = new HasPeriodeHydrator();
        return $hydrator;
    }
}