<?php

namespace Application\Controller\FicheMetier;

use Application\Service\Agent\AgentService;
use Application\Service\FicheMetier\FicheMetierService;
use Zend\Mvc\Controller\ControllerManager;

class FicheMetierControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var FicheMetierService $ficheMetierService
         * @var AgentService $agentService
         */
        $ficheMetierService = $controllerManager->getServiceLocator()->get(FicheMetierService::class);
        $agentService = $controllerManager->getServiceLocator()->get(AgentService::class);

        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setAgentService($agentService);
        return $controller;
    }

}