<?php

namespace Autoform\Form\Champ;

use Zend\Stdlib\Hydrator\HydratorPluginManager;

class ChampHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /** @var ChampHydrator $hydrator */
        $hydrator = new ChampHydrator();
        return $hydrator;
    }
}