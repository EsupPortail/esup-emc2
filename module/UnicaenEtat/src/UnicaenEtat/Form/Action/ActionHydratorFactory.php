<?php

namespace UnicaenEtat\Form\Action;

use Interop\Container\ContainerInterface;

class ActionHydratorFactory {
    /**
     * @param ContainerInterface $container
     * @return ActionHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new ActionHydrator();
        return $hydrator;
    }
}