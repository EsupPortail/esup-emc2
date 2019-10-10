<?php

namespace Application\Form\CompetenceType;

use Interop\Container\ContainerInterface;

class CompetenceTypeHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var CompetenceTypeHydrator $hydrator */
        $hydrator = new CompetenceTypeHydrator();
        return $hydrator;
    }
}