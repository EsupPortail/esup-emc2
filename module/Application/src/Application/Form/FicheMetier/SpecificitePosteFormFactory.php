<?php

namespace Application\Form\FicheMetier;

use Zend\Form\FormElementManager;

class SpecificitePosteFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var FicheMetierCreationHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(SpecificitePosteHydrator::class);

        /** @var FicheMetierCreationForm $form */
        $form = new SpecificitePosteForm();
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}