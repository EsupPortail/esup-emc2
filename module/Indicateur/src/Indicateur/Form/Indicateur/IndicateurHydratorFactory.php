<?php

namespace Indicateur\Form\Indicateur;

use Interop\Container\ContainerInterface;

class IndicateurHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var IndicateurHydrator $hydrator */
        $hydrator = new IndicateurHydrator();
        return $hydrator;
    }
}