<?php

namespace Application\Controller\Agent;

use Application\Form\Agent\AgentForm;
use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Octopus\Service\Individu\IndividuService;
use Zend\Mvc\Controller\ControllerManager;

class AgentControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var AgentService $agentService
         * @var IndividuService $individuService
         * @var RessourceRhService $ressourceService
         */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $individuService = $manager->getServiceLocator()->get(IndividuService::class);
        $ressourceService = $manager->getServiceLocator()->get(RessourceRhService::class);

        /**
         * @var AgentForm $agentForm
         */
        $agentForm = $manager->getServiceLocator()->get('FormElementManager')->get(AgentForm::class);

        /** @var AgentController $controller */
        $controller = new AgentController();

        $controller->setAgentService($agentService);
        $controller->setIndividuService($individuService);
        $controller->setRessourceRhService($ressourceService);

        $controller->setAgentForm($agentForm);

        return $controller;
    }
}