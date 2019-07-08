<?php

namespace Application\Form\Formation;

use Zend\Stdlib\Hydrator\HydratorPluginManager;

class FormationHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /** @var FormationHydrator $hydrator */
        $hydrator = new FormationHydrator();
        return $hydrator;
    }
}