<?php

namespace Application\Form\Affectation;

use Zend\Form\FormElementManager;

class AffectationFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var AffectationHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AffectationHydrator::class);

        $form = new AffectationForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}