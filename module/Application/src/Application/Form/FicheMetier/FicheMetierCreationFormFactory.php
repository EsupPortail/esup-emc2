<?php

namespace Application\Form\FicheMetier;

use Zend\Form\FormElementManager;

class FicheMetierCreationFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var FicheMetierCreationHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FicheMetierCreationHydrator::class);

        /** @var FicheMetierCreationForm $form */
        $form = new FicheMetierCreationForm();
        $form->setHydrator($hydrator);
        $form->init();


        return $form;
    }

}