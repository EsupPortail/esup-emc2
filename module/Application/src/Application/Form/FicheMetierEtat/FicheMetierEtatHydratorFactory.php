<?php

namespace Application\Form\FicheMetierEtat;

use Interop\Container\ContainerInterface;

class FicheMetierEtatHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierEtatHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FicheMetierEtatHydrator $hydrator */
        $hydrator = new FicheMetierEtatHydrator();
        return $hydrator;
    }
}