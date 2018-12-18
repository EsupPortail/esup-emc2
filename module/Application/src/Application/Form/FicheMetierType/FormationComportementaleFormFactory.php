<?php

namespace Application\Form\FicheMetierType;

use Zend\Form\FormElementManager;

class FormationComportementaleFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var FormationComportementaleHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FormationComportementaleHydrator::class);

        $form = new FormationComportementaleForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}