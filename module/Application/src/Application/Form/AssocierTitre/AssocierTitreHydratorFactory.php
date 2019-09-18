<?php

namespace Application\Form\AssocierTitre;

use Interop\Container\ContainerInterface;

class AssocierTitreHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AssocierTitreHydrator $hydrator */
        $hydrator = new AssocierTitreHydrator();
        return $hydrator;
    }
}