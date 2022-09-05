<?php

namespace Formation\Form\Seance;

use Interop\Container\ContainerInterface;

class SeanceHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return SeanceHydrator
     */
    public function __invoke(ContainerInterface $container) : SeanceHydrator
    {
        /** @var SeanceHydrator $hydrator */
        $hydrator = new SeanceHydrator();
        return $hydrator;
    }
}