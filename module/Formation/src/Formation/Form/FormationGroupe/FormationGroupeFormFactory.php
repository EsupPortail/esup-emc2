<?php

namespace Formation\Form\FormationGroupe;

use Interop\Container\ContainerInterface;

class FormationGroupeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationGroupeHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationGroupeHydrator $hydrator  */
        $hydrator = $container->get('HydratorManager')->get(FormationGroupeHydrator::class);

        /** @var FormationGroupeForm $form */
        $form = new FormationGroupeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}