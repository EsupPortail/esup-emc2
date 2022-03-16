<?php

namespace Element\Form\ApplicationGroupe;

use Interop\Container\ContainerInterface;

class ApplicationGroupeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationGroupeHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ApplicationGroupeHydrator $hydrator */
        $hydrator = new ApplicationGroupeHydrator();
        return $hydrator;
    }
}