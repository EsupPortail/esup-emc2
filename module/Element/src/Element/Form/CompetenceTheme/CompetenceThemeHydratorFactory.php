<?php

namespace Element\Form\CompetenceTheme;

use Interop\Container\ContainerInterface;

class CompetenceThemeHydratorFactory {

    public function __invoke(ContainerInterface $container) : CompetenceThemeHydrator
    {
        /** @var CompetenceThemeHydrator $hydrator */
        $hydrator = new CompetenceThemeHydrator();
        return $hydrator;
    }
}