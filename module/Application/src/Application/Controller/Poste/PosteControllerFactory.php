<?php

namespace Application\Controller\Poste;

use Application\Service\Poste\PosteService;
use Zend\Mvc\Controller\ControllerManager;

class PosteControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var PosteService $posteService
         */
        $posteService    = $controllerManager->getServiceLocator()->get(PosteService::class);

        /** @var PosteController $controller */
        $controller = new PosteController();
        $controller->setPosteService($posteService);
        return $controller;
    }
}