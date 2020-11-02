<?php

namespace Formation\Form\FormationInstanceFrais;

use Interop\Container\ContainerInterface;

class FormationInstanceFraisFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFraisForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationInstanceFraisHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationInstanceFraisHydrator::class);

        $form = new FormationInstanceFraisForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}