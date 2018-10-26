<?php

namespace Application\Controller\FicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Zend\Mvc\Controller\ControllerManager;

class FicheMetierControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var FicheMetierService $ficheMetierService
         */
        $ficheMetierService = $controllerManager->getServiceLocator()->get(FicheMetierService::class);

        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();
        $controller->setFicheMetierService($ficheMetierService);
        return $controller;
    }

}