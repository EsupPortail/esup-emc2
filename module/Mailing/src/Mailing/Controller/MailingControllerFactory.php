<?php

namespace Mailing\Controller;

use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use Mailing\Service\MailType\MailTypeService;
use UnicaenUtilisateur\Service\User\UserService;;

class MailingControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MailingService $mailingService
         * @var MailTypeService $typeService
         * @var UserService $userService
         */
        $mailingService = $container->get(MailingService::class);
        $typeService = $container->get(MailTypeService::class);
        $userService = $container->get(UserService::class);

        /** @var  MailingController $controller */
        $controller = new MailingController();
        $controller->setMailingService($mailingService);
        $controller->setMailTypeService($typeService);
        $controller->setUserService($userService);
        return $controller;
    }
}