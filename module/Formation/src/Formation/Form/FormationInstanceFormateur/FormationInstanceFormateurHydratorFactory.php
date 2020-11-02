<?php

namespace Formation\Form\FormationInstanceFormateur;

use Interop\Container\ContainerInterface;

class FormationInstanceFormateurHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFormateurHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        $hydrator = new FormationInstanceFormateurHydrator();
        return $hydrator;
    }
}