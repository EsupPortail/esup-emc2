<?php

namespace Application\Form\RessourceRh;

use Zend\Form\FormElementManager;

class CorrespondanceFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var CorrespondanceHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(CorrespondanceHydrator::class);

        $form = new CorrespondanceForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}