<?php

namespace FicheMetier\Form\TendanceElement;

use Psr\Container\ContainerInterface;

class TendanceElementHydratorFactory
{

    public function __invoke(ContainerInterface $container): TendanceElementHydrator
    {
        $hydrator = new TendanceElementHydrator();
        return $hydrator;
    }
}