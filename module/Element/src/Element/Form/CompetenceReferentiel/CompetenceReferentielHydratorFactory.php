<?php

namespace Element\Form\CompetenceReferentiel;

use Interop\Container\ContainerInterface;

class CompetenceReferentielHydratorFactory {

    public function __invoke(ContainerInterface $container) : CompetenceReferentielHydrator
    {
        /** @var CompetenceReferentielHydrator $hydrator */
        $hydrator = new CompetenceReferentielHydrator();
        return $hydrator;
    }
}