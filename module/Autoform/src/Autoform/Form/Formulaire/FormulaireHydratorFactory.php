<?php

namespace Autoform\Form\Formulaire;

use Zend\Stdlib\Hydrator\HydratorPluginManager;

class FormulaireHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /** @var FormulaireHydrator $hydrator */
        $hydrator = new FormulaireHydrator();
        return $hydrator;
    }
}