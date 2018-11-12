<?php

namespace Application\Controller\Affectation;

use Application\Service\Affectation\AffectationService;
use Zend\Mvc\Controller\ControllerManager;

class AffectationControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /** @var AffectationService $affectationService */
        $affectationService = $controllerManager->getServiceLocator()->get(AffectationService::class);

        /** @var AffectationController $controller */
        $controller = new AffectationController();
        $controller->setAffectationService($affectationService);
        return $controller;
    }
}