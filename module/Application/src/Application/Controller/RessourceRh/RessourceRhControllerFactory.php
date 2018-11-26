<?php

namespace Application\Controller\RessourceRh;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\Mvc\Controller\ControllerManager;

class RessourceRhControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var RessourceRhService $ressourceService
         */
        $ressourceService    = $controllerManager->getServiceLocator()->get(RessourceRhService::class);

        /** @var RessourceRhController $controller */
        $controller = new RessourceRhController();
        $controller->setRessourceRhService($ressourceService);
        return $controller;
    }

}