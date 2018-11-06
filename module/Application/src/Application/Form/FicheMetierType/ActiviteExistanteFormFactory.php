<?php

namespace Application\Form\FicheMetierType;

use Zend\Form\FormElementManager;

class ActiviteExistanteFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var ActiviteExistanteHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(ActiviteExistanteHydrator::class);

        $form = new ActiviteExistanteForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}