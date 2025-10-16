<?php

namespace Metier\Form\Domaine;

use Interop\Container\ContainerInterface;

class DomaineHydratorFactory
{

    public function __invoke(ContainerInterface $container): DomaineHydrator
    {
        $hydrator = new DomaineHydrator();
        return $hydrator;
    }
}