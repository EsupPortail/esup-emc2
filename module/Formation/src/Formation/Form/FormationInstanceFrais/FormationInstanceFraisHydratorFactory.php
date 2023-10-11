<?php

namespace Formation\Form\FormationInstanceFrais;

use Interop\Container\ContainerInterface;

class FormationInstanceFraisHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFraisHydrator
     */
    public function __invoke(ContainerInterface $container): FormationInstanceFraisHydrator
    {
        $hydrator = new FormationInstanceFraisHydrator();
        return $hydrator;
    }
}