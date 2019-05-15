<?php

namespace Application\Form\FicheMetier;

use Zend\Form\FormElementManager;

class FormationBaseFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var FormationBaseHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FormationBaseHydrator::class);

        $form = new FormationBaseForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}