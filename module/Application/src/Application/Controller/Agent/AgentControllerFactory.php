<?php

namespace Application\Controller\Agent;

use Application\Form\Agent\AgentForm;
use Application\Form\Agent\AgentImportForm;
use Application\Service\Agent\AgentService;
use Octopus\Service\Individu\IndividuService;
use Zend\Mvc\Controller\ControllerManager;

class AgentControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var AgentService $agentService
         * @var IndividuService $individuService
         */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $individuService = $manager->getServiceLocator()->get(IndividuService::class);

        /**
         * @var AgentForm $agentForm
         * @var AgentImportForm $agentImportForm
         */
        $agentForm = $manager->getServiceLocator()->get('FormElementManager')->get(AgentForm::class);
        $agentImportForm = $manager->getServiceLocator()->get('FormElementManager')->get(AgentImportForm::class);

        /** @var AgentController $controller */
        $controller = new AgentController();

        $controller->setAgentService($agentService);
        $controller->setIndividuService($individuService);

        $controller->setAgentForm($agentForm);
        $controller->setAgentImportForm($agentImportForm);

        return $controller;
    }
}