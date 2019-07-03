<?php

namespace Application\Form\FicheMetier;

use Zend\Form\FormElementManager;

class FormationOperationnelleFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var FormationOperationnelleHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FormationOperationnelleHydrator::class);

        $form = new FormationOperationnelleForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}