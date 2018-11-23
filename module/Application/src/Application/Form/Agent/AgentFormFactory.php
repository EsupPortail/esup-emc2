<?php

namespace Application\Form\Agent;

use Zend\Form\FormElementManager;

class AgentFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var AgentHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AgentHydrator::class);

        $form = new AgentForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}