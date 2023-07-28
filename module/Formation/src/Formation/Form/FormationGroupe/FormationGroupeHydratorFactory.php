<?php

namespace Formation\Form\FormationGroupe;

use Interop\Container\ContainerInterface;

class FormationGroupeHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationGroupeHydrator
     */
    public function __invoke(ContainerInterface $container): FormationGroupeHydrator
    {
        /** @var FormationGroupeHydrator $hydrator */
        $hydrator = new FormationGroupeHydrator();
        return $hydrator;
    }
}