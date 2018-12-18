<?php

namespace Application\Form\RessourceRh;

use Zend\Form\FormElementManager;

class AgentStatusFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var AgentStatusHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AgentStatusHydrator::class);

        $form = new AgentStatusForm();
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}