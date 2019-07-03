<?php

namespace Application\Form\MissionSpecifique;

use Zend\Stdlib\Hydrator\HydratorPluginManager;

class MissionSpecifiqueHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /** @var MissionSpecifiqueHydrator $hydrator */
        $hydrator = new MissionSpecifiqueHydrator();
        return $hydrator;
    }
}