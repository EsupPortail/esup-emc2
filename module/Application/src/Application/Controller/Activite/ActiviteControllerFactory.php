<?php

namespace Application\Controller\Activite;

use Application\Service\Activite\ActiviteService;
use Zend\Mvc\Controller\ControllerManager;

class ActiviteControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var ActiviteService $activiteService
         */
        $activiteService = $controllerManager->getServiceLocator()->get(ActiviteService::class);

        /** @var ActiviteController $controller */
        $controller = new ActiviteController();
        $controller->setActiviteService($activiteService);
        return $controller;
    }
}