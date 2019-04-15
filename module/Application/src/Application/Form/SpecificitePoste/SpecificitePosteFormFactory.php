<?php

namespace Application\Form\SpecificitePoste;

use Zend\Form\FormElementManager;

class SpecificitePosteFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var SpecificitePosteHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(SpecificitePosteHydrator::class);

        /** @var SpecificitePosteForm $form */
        $form = new SpecificitePosteForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}