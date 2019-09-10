<?php

namespace Autoform\Form\Champ;

use Interop\Container\ContainerInterface;

class ChampHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var ChampHydrator $hydrator */
        $hydrator = new ChampHydrator();
        return $hydrator;
    }
}