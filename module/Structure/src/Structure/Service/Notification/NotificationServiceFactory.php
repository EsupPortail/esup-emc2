<?php

namespace Structure\Service\Notification;

use Application\Service\Macro\MacroService;
use Application\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenMail\Service\Mail\MailService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class NotificationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NotificationService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NotificationService
    {
        /**
         * @var MailService $mailService
         * @var RenduService $renduService
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UrlService $urlService
         * @var MacroService $macroService
         * @var UserService $userService
         */
        $mailService = $container->get(MailService::class);
        $renduService = $container->get(RenduService::class);
        $roleService = $container->get(RoleService::class);
        $structureService = $container->get(StructureService::class);
        $urlService = $container->get(UrlService::class);
        $macroService = $container->get(MacroService::class);
        $userService = $container->get(UserService::class);

        $service = new NotificationService();
        $service->setMailService($mailService);
        $service->setRenduService($renduService);
        $service->setRoleService($roleService);
        $service->setStructureService($structureService);
        $service->setUrlService($urlService);
        $service->setMacroService($macroService);
        $service->setUserService($userService);
        return $service;
    }
}