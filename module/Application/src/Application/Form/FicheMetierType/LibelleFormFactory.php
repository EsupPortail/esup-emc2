<?php

namespace Application\Form\FicheMetierType;

use Zend\Form\FormElementManager;

class LibelleFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var LibelleForm $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(LibelleHydrator::class);

        $form = new LibelleForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}