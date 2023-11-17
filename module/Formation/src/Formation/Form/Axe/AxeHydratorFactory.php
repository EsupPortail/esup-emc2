<?php

namespace Formation\Form\Axe;

use Interop\Container\ContainerInterface;

class AxeHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return AxeHydrator
     */
    public function __invoke(ContainerInterface $container): AxeHydrator
    {
        /** @var AxeHydrator $hydrator */
        $hydrator = new AxeHydrator();
        return $hydrator;
    }
}