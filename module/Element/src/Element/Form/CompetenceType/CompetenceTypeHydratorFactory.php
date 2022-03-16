<?php

namespace Element\Form\CompetenceType;

use Interop\Container\ContainerInterface;

class CompetenceTypeHydratorFactory {

    public function __invoke(ContainerInterface $container) : CompetenceTypeHydrator
    {
        /** @var CompetenceTypeHydrator $hydrator */
        $hydrator = new CompetenceTypeHydrator();
        return $hydrator;
    }
}