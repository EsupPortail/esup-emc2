<?php

namespace Application\Form\FormationTheme;

use Interop\Container\ContainerInterface;

class FormationThemeHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationThemeHydrator $hydrator */
        $hydrator = new FormationThemeHydrator();
        return $hydrator;
    }
}