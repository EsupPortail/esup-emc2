<?php

namespace Application\Form\FicheMetier;

use Interop\Container\ContainerInterface;

class FormationOperationnelleFormFactory{

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationOperationnelleHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationOperationnelleHydrator::class);

        $form = new FormationOperationnelleForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}