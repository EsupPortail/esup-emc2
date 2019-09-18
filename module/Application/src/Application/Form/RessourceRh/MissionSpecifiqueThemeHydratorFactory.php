<?php

namespace Application\Form\RessourceRh;

use Interop\Container\ContainerInterface;

class MissionSpecifiqueThemeHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MissionSpecifiqueThemeHydrator $hydrator */
        $hydrator = new MissionSpecifiqueThemeHydrator();
        return $hydrator;
    }
}