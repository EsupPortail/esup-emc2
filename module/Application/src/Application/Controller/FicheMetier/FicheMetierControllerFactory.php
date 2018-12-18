<?php

namespace Application\Controller\FicheMetier;

use Application\Service\Activite\ActiviteService;
use Application\Service\Agent\AgentService;
use Application\Service\FicheMetier\FicheMetierService;
use Zend\Mvc\Controller\ControllerManager;

class FicheMetierControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var FicheMetierService $ficheMetierService
         * @var AgentService $agentService
         * @var ActiviteService $activiteService
         */
        $ficheMetierService = $controllerManager->getServiceLocator()->get(FicheMetierService::class);
        $agentService = $controllerManager->getServiceLocator()->get(AgentService::class);
        $activiteService = $controllerManager->getServiceLocator()->get(ActiviteService::class);

        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setAgentService($agentService);
        $controller->setActiviteService($activiteService);
        return $controller;
    }

}