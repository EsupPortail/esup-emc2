<?php

namespace Application\Controller\Poste;

use Application\Service\Poste\PosteService;
use Octopus\Service\Immobilier\ImmobilierService;
use Zend\Mvc\Controller\ControllerManager;

class PosteControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var PosteService $posteService
         * @var ImmobilierService $immobilierService
         */
        $posteService    = $controllerManager->getServiceLocator()->get(PosteService::class);
        $immobilierService    = $controllerManager->getServiceLocator()->get(ImmobilierService::class);

        /** @var PosteController $controller */
        $controller = new PosteController();
        $controller->setPosteService($posteService);
        $controller->setImmobiliserService($immobilierService);
        return $controller;
    }
}