<?php

namespace Application\Form\MissionSpecifique;

use Zend\Form\FormElementManager;

class MissionSpecifiqueFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var MissionSpecifiqueHydrator $hydrator
         */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(MissionSpecifiqueHydrator::class);

        /**
         * @var MissionSpecifiqueForm $form
         */
        $form = new MissionSpecifiqueForm();
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }
}