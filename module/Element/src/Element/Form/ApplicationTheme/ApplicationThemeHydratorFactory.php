<?php

namespace Element\Form\ApplicationTheme;

use Interop\Container\ContainerInterface;

class ApplicationThemeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationThemeHydrator
     */
    public function __invoke(ContainerInterface $container) : ApplicationThemeHydrator
    {
        $hydrator = new ApplicationThemeHydrator();
        return $hydrator;
    }
}