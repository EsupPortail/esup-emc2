<?php

namespace Application\Controller\Affectation;

use Zend\Mvc\Controller\ControllerManager;

class AffectationControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /** @var AffectationController $controller */
        $controller = new AffectationController();
        return $controller;
    }
}