<?php

namespace Autoform\Form\Formulaire;

use Interop\Container\ContainerInterface;

class FormulaireHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormulaireHydrator $hydrator */
        $hydrator = new FormulaireHydrator();
        return $hydrator;
    }
}