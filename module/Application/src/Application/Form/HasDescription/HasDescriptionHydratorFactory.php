<?php

namespace Application\Form\HasDescription;

use Interop\Container\ContainerInterface;

class HasDescriptionHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return HasDescriptionHydrator
     */
    public function __invoke(ContainerInterface $container) : HasDescriptionHydrator
    {
        $hydrator = new HasDescriptionHydrator();
        return $hydrator;
    }
}