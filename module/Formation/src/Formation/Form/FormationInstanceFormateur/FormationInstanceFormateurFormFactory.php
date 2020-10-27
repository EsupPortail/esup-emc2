<?php

namespace Formation\Form\FormationInstanceFormateur;

use Interop\Container\ContainerInterface;

class FormationInstanceFormateurFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFormateurForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationInstanceFormateurHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationInstanceFormateurHydrator::class);

        $form = new FormationInstanceFormateurForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}