<?php

namespace Element\Form\CompetenceReferentiel;

use Interop\Container\ContainerInterface;

class CompetenceReferentielHydratorFactory {

    public function __invoke(ContainerInterface $container) : CompetenceReferentielHydrator
    {
        $hydrator = new CompetenceReferentielHydrator();
        return $hydrator;
    }
}