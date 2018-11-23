<?php

namespace Application\Form\Application;

use Zend\Form\FormElementManager;

class ApplicationFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var ApplicationHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(ApplicationHydrator::class);

        $form = new ApplicationForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}