<?php

namespace Application\Form\FichePosteCreation;

use Interop\Container\ContainerInterface;

class FichePosteCreationHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new FichePosteCreationHydrator();

        return $hydrator;
    }
}