<?php

namespace Application\Form\FicheMetier;

use Zend\Form\FormElementManager;

class MissionsPrincipalesFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var MissionsPrincipalesHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(MissionsPrincipalesHydrator::class);

        $form = new MissionsPrincipalesForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}