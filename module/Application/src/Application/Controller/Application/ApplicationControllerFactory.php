<?php

namespace Application\Controller\Application;

use Application\Service\Application\ApplicationService;
use Zend\Mvc\Controller\ControllerManager;

class ApplicationControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $controllerManager->getServiceLocator()->get(ApplicationService::class);

        /** @var ApplicationController $controller */
        $controller = new ApplicationController();
        $controller->setApplicationService($applicationService);
        return $controller;
    }
}