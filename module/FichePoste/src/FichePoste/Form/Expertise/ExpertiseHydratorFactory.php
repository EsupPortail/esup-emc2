<?php

namespace FichePoste\Form\Expertise;

use Psr\Container\ContainerInterface;

class ExpertiseHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ExpertiseHydrator
     */
    public function __invoke(ContainerInterface $container): ExpertiseHydrator
    {
        $hydrator = new ExpertiseHydrator();
        return $hydrator;
    }
}
