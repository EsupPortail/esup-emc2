<?php

namespace Indicateur\Form\Indicateur;

use Zend\Stdlib\Hydrator\HydratorPluginManager;

class IndicateurHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /** @var IndicateurHydrator $hydrator */
        $hydrator = new IndicateurHydrator();
        return $hydrator;
    }
}