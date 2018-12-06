<?php

namespace Application\Form\Agent;

use Zend\Form\FormElementManager;

class MissionComplementaireFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var MissionComplementaireHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(MissionComplementaireHydrator::class);

        $form = new MissionComplementaireForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}