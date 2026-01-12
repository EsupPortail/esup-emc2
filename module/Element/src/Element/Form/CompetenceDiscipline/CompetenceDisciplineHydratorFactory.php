<?php

namespace Element\Form\CompetenceDiscipline;

use Interop\Container\ContainerInterface;

class CompetenceDisciplineHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceDisciplineHydrator
     */
    public function __invoke(ContainerInterface $container) : CompetenceDisciplineHydrator
    {
        $hydrator = new CompetenceDisciplineHydrator();
        return $hydrator;
    }
}