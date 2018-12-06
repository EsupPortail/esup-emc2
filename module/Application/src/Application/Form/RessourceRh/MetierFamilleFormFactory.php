<?php

namespace Application\Form\RessourceRh;

use Zend\Form\FormElementManager;

class MetierFamilleFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var MetierFamilleHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(MetierFamilleHydrator::class);

        $form = new MetierFamilleForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}