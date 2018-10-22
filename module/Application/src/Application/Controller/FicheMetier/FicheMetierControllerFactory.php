<?php

namespace Application\Controller\FicheMetier;

use Zend\Mvc\Controller\ControllerManager;

class FicheMetierControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();
        return $controller;
    }

}