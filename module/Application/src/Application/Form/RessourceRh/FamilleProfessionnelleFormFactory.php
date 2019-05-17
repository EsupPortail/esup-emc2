<?php

namespace Application\Form\RessourceRh;

use Zend\Form\FormElementManager;

class FamilleProfessionnelleFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var FamilleProfessionnelleHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FamilleProfessionnelleHydrator::class);

        $form = new FamilleProfessionnelleForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}