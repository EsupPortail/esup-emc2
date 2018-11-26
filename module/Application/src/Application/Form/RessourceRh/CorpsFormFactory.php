<?php

namespace Application\Form\RessourceRh;

use Zend\Form\FormElementManager;

class CorpsFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var CorpsHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(CorpsHydrator::class);

        $form = new CorpsForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}