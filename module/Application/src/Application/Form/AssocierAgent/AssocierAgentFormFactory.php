<?php

namespace Application\Form\AssocierAgent;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager;

class AssocierAgentFormFactory {

    public function __invoke(ContainerInterface $container)
    {

        /** @var AgentService $agentService */
        $agentService = $container->get(AgentService::class);
        /** @var AssocierAgentHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AssocierAgentHydrator::class);

        /** @var AssocierAgentForm $form */
        $form = new AssocierAgentForm();
        $form->setAgentService($agentService);
        $form->setHydrator($hydrator);
        $form->init();


        return $form;
    }

}