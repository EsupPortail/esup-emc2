<?php

namespace Application\Form\RessourceRh;

use Zend\Form\FormElementManager;

class DomaineFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var DomaineHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(DomaineHydrator::class);

        $form = new DomaineForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}