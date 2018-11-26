<?php

namespace Application\Controller\RessourceRh;

use Application\Service\Metier\MetierService;
use Application\Service\RessourceRh\RessourceRhService;
use Zend\Mvc\Controller\ControllerManager;

class RessourceRhControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var MetierService $metierService
         * @var RessourceRhService $ressourceService
         */
        $metierService       = $controllerManager->getServiceLocator()->get(MetierService::class);
        $ressourceService    = $controllerManager->getServiceLocator()->get(RessourceRhService::class);

        /** @var RessourceRhController $controller */
        $controller = new RessourceRhController();
        $controller->setMetierService($metierService);
        $controller->setRessourceRhService($ressourceService);
        return $controller;
    }

}