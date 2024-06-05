<?php

namespace Formation\Form\Session;

use Interop\Container\ContainerInterface;

class SessionHydratorFactory
{
    public function __invoke(ContainerInterface $container): SessionHydrator
    {
        $hydrator = new SessionHydrator();
        return $hydrator;
    }
}