<?php

namespace Mailing\Controller;

use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use UnicaenUtilisateur\Service\User\UserService;;

class MailingControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MailingService $mailingService
         * @var UserService $userService
         */
        $mailingService = $container->get(MailingService::class);
        $userService = $container->get(UserService::class);

        /** @var  MailingController $controller */
        $controller = new MailingController();
        $controller->setMailingService($mailingService);
        $controller->setUserService($userService);
        return $controller;
    }
}