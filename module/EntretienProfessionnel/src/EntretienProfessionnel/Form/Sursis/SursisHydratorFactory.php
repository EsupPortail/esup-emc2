<?php

namespace EntretienProfessionnel\Form\Sursis;

use Interop\Container\ContainerInterface;

class SursisHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SursisHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new SursisHydrator();
        return $hydrator;
    }
}