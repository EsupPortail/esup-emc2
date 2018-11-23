<?php

namespace Application\Form\Activite;

use Zend\Form\FormElementManager;

class ActiviteFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var ActiviteHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(ActiviteHydrator::class);

        $form = new ActiviteForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}