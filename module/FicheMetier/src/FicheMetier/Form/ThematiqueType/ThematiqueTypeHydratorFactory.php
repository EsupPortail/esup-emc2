<?php

namespace FicheMetier\Form\ThematiqueType;

use Psr\Container\ContainerInterface;

class ThematiqueTypeHydratorFactory {

    public function __invoke(ContainerInterface $container): ThematiqueTypeHydrator
    {
        $hydrator = new ThematiqueTypeHydrator();
        return $hydrator;
    }
}