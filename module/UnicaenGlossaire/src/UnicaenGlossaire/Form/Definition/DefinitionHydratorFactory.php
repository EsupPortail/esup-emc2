<?php

namespace UnicaenGlossaire\Form\Definition;

use Interop\Container\ContainerInterface;

class DefinitionHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return DefinitionHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new DefinitionHydrator();
        return $hydrator;
    }
}