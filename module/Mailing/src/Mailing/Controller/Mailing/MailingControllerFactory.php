<?php

namespace Mailing\Controller\Mailing;

use Application\Service\User\UserService;
use Mailing\Service\Mailing\MailingService;
use Zend\Mvc\Controller\ControllerManager;

class MailingControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /**
         * @var MailingService $mailingService
         * @var UserService $userService
         */
        $mailingService = $controllerManager->getServiceLocator()->get(MailingService::class);
        $userService = $controllerManager->getServiceLocator()->get(UserService::class);

        /** @var  MailingController $controller */
        $controller = new MailingController();
        $controller->setMailingService($mailingService);
        $controller->setUserService($userService);
        return $controller;
    }
}