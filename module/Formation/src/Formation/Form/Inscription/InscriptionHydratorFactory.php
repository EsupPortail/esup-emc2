<?php

namespace Formation\Form\Inscription;

use Psr\Container\ContainerInterface;

class InscriptionHydratorFactory {

    public function __invoke(ContainerInterface $container) : InscriptionHydrator
    {
        $hydrator = new InscriptionHydrator();
        return $hydrator;
    }
}