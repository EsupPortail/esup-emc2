<?php

namespace Formation\Form\SessionParametre;

use Psr\Container\ContainerInterface;

class SessionParametreHydratorFactory {

    public function __invoke(ContainerInterface $container) : SessionParametreHydrator
    {
        $hydrator = new SessionParametreHydrator();
        return $hydrator;
    }
}