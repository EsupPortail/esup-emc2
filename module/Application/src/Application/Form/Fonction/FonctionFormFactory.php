<?php

namespace Application\Form\Fonction;

use Zend\Form\FormElementManager;

class FonctionFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var FonctionHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FonctionHydrator::class);

        /** @var FonctionForm */
        $form = new FonctionForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}