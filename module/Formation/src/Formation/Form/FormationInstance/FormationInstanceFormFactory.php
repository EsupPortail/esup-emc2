<?php

namespace Formation\Form\FormationInstance;

use Interop\Container\ContainerInterface;

class FormationInstanceFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationInstanceHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationInstanceHydrator::class);

        /** @var FormationInstanceForm $form */
        $form = new FormationInstanceForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}