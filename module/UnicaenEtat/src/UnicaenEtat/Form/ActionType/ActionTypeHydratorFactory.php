<?php

namespace UnicaenEtat\Form\ActionType;

use Interop\Container\ContainerInterface;

class ActionTypeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ActionTypeHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new ActionTypeHydrator();
        return $hydrator;
    }
}