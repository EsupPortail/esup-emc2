<?php

namespace Formation\Form\Formateur;

use Interop\Container\ContainerInterface;

class FormateurHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormateurHydrator
     */
    public function __invoke(ContainerInterface $container) : FormateurHydrator
    {
        $hydrator = new FormateurHydrator();
        return $hydrator;
    }
}