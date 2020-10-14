<?php

namespace UnicaenEtat\Form\EtatType;

use Interop\Container\ContainerInterface;

class EtatTypeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return EtatTypeHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new EtatTypeHydrator();
        return $hydrator;
    }
}