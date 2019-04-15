<?php

namespace Application\Form\AssocierAgent;

use Application\Service\Agent\AgentService;
use Zend\Form\FormElementManager;

class AssocierAgentFormFactory {

    public function __invoke(FormElementManager $manager)
    {

        /** @var AgentService $agentService */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        /** @var AssocierAgentHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AssocierAgentHydrator::class);

        /** @var AssocierAgentForm $form */
        $form = new AssocierAgentForm();
        $form->setAgentService($agentService);
        $form->setHydrator($hydrator);
        $form->init();


        return $form;
    }

}