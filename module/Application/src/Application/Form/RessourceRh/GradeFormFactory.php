<?php

namespace Application\Form\RessourceRh;

use Zend\Form\FormElementManager;

class GradeFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var GradeHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(GradeHydrator::class);

        $form = new GradeForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}