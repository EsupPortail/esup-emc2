<?php

namespace Application\Controller;

use Application\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class IndexControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var UserService $userService
         */
        $userService = $controllerManager->getServiceLocator()->get(UserService::class);

        /** @var IndexController $controller */
        $controller = new IndexController();
        $controller->setUserService($userService);
        return $controller;
    }

}