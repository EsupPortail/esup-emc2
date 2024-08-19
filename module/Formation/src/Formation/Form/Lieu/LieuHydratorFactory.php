<?php

namespace Formation\Form\Lieu;

use Psr\Container\ContainerInterface;

class LieuHydratorFactory {

    public function __invoke(ContainerInterface $container): LieuHydrator
    {
        $hydrator = new LieuHydrator();
        return $hydrator;
    }
}