<?php

namespace Formation\Form\FormationTheme;

use Interop\Container\ContainerInterface;

class FormationThemeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationThemeHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationThemeHydrator $hydrator */
        $hydrator = new FormationThemeHydrator();
        return $hydrator;
    }
}