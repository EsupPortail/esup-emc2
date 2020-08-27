<?php

namespace Application\Form\FonctionDestination;

use Interop\Container\ContainerInterface;

class FonctionDestinationHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return FonctionDestinationHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FonctionDestinationHydrator $hydrator */
        $hydrator = new FonctionDestinationHydrator();
        return $hydrator;
    }
}