<?php

namespace Application\Form\Raison;

use Psr\Container\ContainerInterface;

class RaisonHydratorFactory {

    public function __invoke(ContainerInterface $container) : RaisonHydrator
    {
        $hydrator = new RaisonHydrator();
        return $hydrator;
    }
}