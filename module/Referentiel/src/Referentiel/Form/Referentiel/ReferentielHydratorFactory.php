<?php

namespace Referentiel\Form\Referentiel;

use Psr\Container\ContainerInterface;

class ReferentielHydratorFactory
{

    public function __invoke(ContainerInterface $container): ReferentielHydrator
    {
        $hydrator = new ReferentielHydrator();
        return $hydrator;
    }
}
