<?php

namespace Application\Form\Formation;

use Zend\Form\FormElementManager;

class FormationFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var FormationHydrator $hydrator
         */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FormationHydrator::class);

        /** @var FormationForm $form */
        $form = new FormationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}