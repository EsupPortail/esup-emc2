<?php

namespace Application\Form\Formation;

use Interop\Container\ContainerInterface;

class FormationHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationHydrator $hydrator */
        $hydrator = new FormationHydrator();
        return $hydrator;
    }
}