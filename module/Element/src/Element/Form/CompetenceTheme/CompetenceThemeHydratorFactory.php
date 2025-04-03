<?php

namespace Element\Form\CompetenceTheme;

use Interop\Container\ContainerInterface;

class CompetenceThemeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceThemeHydrator
     */
    public function __invoke(ContainerInterface $container) : CompetenceThemeHydrator
    {
        $hydrator = new CompetenceThemeHydrator();
        return $hydrator;
    }
}