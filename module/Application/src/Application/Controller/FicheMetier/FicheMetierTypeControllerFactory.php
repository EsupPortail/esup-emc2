<?php

namespace Application\Controller\FicheMetier;

use Application\Service\Activite\ActiviteService;
use Application\Service\FicheMetier\FicheMetierService;
use Zend\Mvc\Controller\ControllerManager;

class FicheMetierTypeControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var ActiviteService $activiteService
         * @var FicheMetierService $ficheMetierService
         */
        $activiteService = $controllerManager->getServiceLocator()->get(ActiviteService::class);
        $ficheMetierService = $controllerManager->getServiceLocator()->get(FicheMetierService::class);

        /** @var FicheMetierControllerFactory.php $controller */
        $controller = new FicheMetierTypeController();
        $controller->setActiviteService($activiteService);
        $controller->setFicheMetierService($ficheMetierService);
        return $controller;
    }

}