<?php

namespace Application\Form\FichePosteCreation;

use Zend\Form\FormElementManager;

class FichePosteCreationFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var FichePosteCreationHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FichePosteCreationHydrator::class);

        /** @var FichePosteCreationForm $form */
        $form = new FichePosteCreationForm();
        $form->setHydrator($hydrator);
        $form->init();


        return $form;
    }

}