<?php

namespace Formation\Form\Justificatif;

use Psr\Container\ContainerInterface;

class JustificatifHydratorFactory {

    public function __invoke(ContainerInterface $container) : JustificatifHydrator
    {
        $hydrator = new JustificatifHydrator();
        return $hydrator;
    }
}