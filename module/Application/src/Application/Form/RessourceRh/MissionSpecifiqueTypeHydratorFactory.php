<?php

namespace Application\Form\RessourceRh;

use Interop\Container\ContainerInterface;

class MissionSpecifiqueTypeHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MissionSpecifiqueTypeHydrator $hydrator */
        $hydrator = new MissionSpecifiqueTypeHydrator();
        return $hydrator;
    }
}