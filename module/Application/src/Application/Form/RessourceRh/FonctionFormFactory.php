<?php

namespace Application\Form\RessourceRh;

use Zend\Form\FormElementManager;

class FonctionFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var FonctionHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FonctionHydrator::class);

        $form = new FonctionForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}