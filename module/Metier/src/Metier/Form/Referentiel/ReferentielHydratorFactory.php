<?php

namespace Metier\Form\Referentiel;

use Interop\Container\ContainerInterface;

class ReferentielHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferentielHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ReferentielHydrator $hydrator */
        $hydrator = new ReferentielHydrator();
        return $hydrator;
    }
}