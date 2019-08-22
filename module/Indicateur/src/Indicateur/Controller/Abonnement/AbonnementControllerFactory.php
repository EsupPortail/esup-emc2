<?php

namespace Indicateur\Controller\Abonnement;

use Zend\Mvc\Controller\ControllerManager;

class AbonnementControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /** @var AbonnementController $controller */
        $controller = new AbonnementController();
        return $controller;
    }
}