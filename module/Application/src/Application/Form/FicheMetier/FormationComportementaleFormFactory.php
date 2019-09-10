<?php

namespace Application\Form\FicheMetier;

use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager;

class FormationComportementaleFormFactory{

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationComportementaleHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormationComportementaleHydrator::class);

        $form = new FormationComportementaleForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}