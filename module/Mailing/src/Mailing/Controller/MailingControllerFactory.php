<?php

namespace Mailing\Controller;

use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use Mailing\Service\MailType\MailTypeService;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenUtilisateur\Service\User\UserService;;

class MailingControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         * @var MailingService $mailingService
         * @var MailTypeService $typeService
         * @var UserService $userService
         */
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $mailingService = $container->get(MailingService::class);
        $typeService = $container->get(MailTypeService::class);
        $userService = $container->get(UserService::class);

        /** @var  MailingController $controller */
        $controller = new MailingController();
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setMailingService($mailingService);
        $controller->setMailTypeService($typeService);
        $controller->setUserService($userService);
        return $controller;
    }
}