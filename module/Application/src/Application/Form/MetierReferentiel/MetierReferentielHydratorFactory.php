<?php

namespace Application\Form\MetierReferentiel;

use Interop\Container\ContainerInterface;

class MetierReferentielHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierReferentielHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var MetierReferentielHydrator $hydrator */
        $hydrator = new MetierReferentielHydrator();
        return $hydrator;
    }
}