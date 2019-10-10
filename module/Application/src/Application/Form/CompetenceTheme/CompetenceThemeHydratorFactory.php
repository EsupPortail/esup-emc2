<?php

namespace Application\Form\CompetenceTheme;

use Interop\Container\ContainerInterface;

class CompetenceThemeHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var CompetenceThemeHydrator $hydrator */
        $hydrator = new CompetenceThemeHydrator();
        return $hydrator;
    }
}