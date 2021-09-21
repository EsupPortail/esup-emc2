<?php

namespace UnicaenDocument\Form\Macro;

use Interop\Container\ContainerInterface;

class MacroHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return MacroHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new MacroHydrator();
        return $hydrator;
    }
}