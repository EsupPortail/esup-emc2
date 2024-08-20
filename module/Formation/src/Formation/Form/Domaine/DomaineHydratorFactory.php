<?php

namespace Formation\Form\Domaine;

use Interop\Container\ContainerInterface;

class DomaineHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return DomaineHydrator
     */
    public function __invoke(ContainerInterface $container): DomaineHydrator
    {
        $hydrator = new DomaineHydrator();
        return $hydrator;
    }
}