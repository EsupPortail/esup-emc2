<?php

namespace EmploiRepere\Form\EmploiRepere;

use Psr\Container\ContainerInterface;

class EmploiReperehydratorFactory
{
    public function __invoke(ContainerInterface $container): EmploiRepereHydrator
    {
        $hydrator = new EmploiRepereHydrator();
        return $hydrator;
    }
}
