<?php

namespace FicheMetier\Form\TendanceType;

use Psr\Container\ContainerInterface;

class TendanceTypeHydratorFactory {

    public function __invoke(ContainerInterface $container): TendanceTypeHydrator
    {
        $hydrator = new TendanceTypeHydrator();
        return $hydrator;
    }
}