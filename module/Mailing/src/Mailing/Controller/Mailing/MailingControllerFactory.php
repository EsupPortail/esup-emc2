<?php

namespace Mailing\Controller\Mailing;

use Zend\Mvc\Controller\ControllerManager;

class MailingControllerFactory {

    public function __invoke(ControllerManager $controllerManager)
    {
        /** @var  MailingController $controller */
        $controller = new MailingController();
        return $controller;
    }
}