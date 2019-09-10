<?php

namespace Application\Form\FicheMetier;

use Interop\Container\ContainerInterface;

class FormationBaseFormFactory{

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationBaseHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationBaseHydrator::class);

        $form = new FormationBaseForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}