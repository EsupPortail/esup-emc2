<?php

namespace Application\Form\MissionSpecifique;

use Interop\Container\ContainerInterface;

class MissionSpecifiqueHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MissionSpecifiqueHydrator $hydrator */
        $hydrator = new MissionSpecifiqueHydrator();
        return $hydrator;
    }
}