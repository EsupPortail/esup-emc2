<?php

namespace Application\Controller\Metier;

use Application\Service\Metier\MetierService;
use Zend\Mvc\Controller\ControllerManager;

class MetierControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var MetierService $metierService
         */
        $metierService = $controllerManager->getServiceLocator()->get(MetierService::class);

        /** @var MetierController $controller */
        $controller = new MetierController();
        $controller->setMetierService($metierService);
        return $controller;
    }
}