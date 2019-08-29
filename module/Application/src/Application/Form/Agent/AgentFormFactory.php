<?php

namespace Application\Form\Agent;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\Form\FormElementManager;

class AgentFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var AgentHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AgentHydrator::class);

        /** @var AgentForm $form */
        $form = new AgentForm();
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }
}