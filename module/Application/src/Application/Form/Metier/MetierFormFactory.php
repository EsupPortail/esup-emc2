<?php

namespace Application\Form\Metier;

use Zend\Form\FormElementManager;

class MetierFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var MetierHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(MetierHydrator::class);

        $form = new MetierForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}