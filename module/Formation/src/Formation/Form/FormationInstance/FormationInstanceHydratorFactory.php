<?php

namespace Formation\Form\FormationInstance;

use Interop\Container\ContainerInterface;

class FormationInstanceHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceHydrator
     */
    public function __invoke(ContainerInterface $container): FormationInstanceHydrator
    {
        /** @var FormationInstanceHydrator $hydrator */
        $hydrator = new FormationInstanceHydrator();
        return $hydrator;
    }
}