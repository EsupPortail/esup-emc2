<?php

namespace Formation\Service\Notification;

use Application\Service\Agent\AgentService;
use Application\Service\Macro\MacroService;
use Formation\Service\Url\UrlService;
use Psr\Container\Containerinterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
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
         * @var AgentService $agentService
         * @var MailService $mailService
         * @var ParametreService $parametreService
         * @var RenduService $renduService
         * @var RoleService $roleService
         * @var UrlService $urlService
         * @var MacroService $macroService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $roleService = $container->get(RoleService::class);
        $urlService = $container->get(UrlService::class);
        $macroService = $container->get(MacroService::class);
        $userService = $container->get(UserService::class);

        $service = new NotificationService();
        $service->setAgentService($agentService);
        $service->setMailService($mailService);
        $service->setParametreService($parametreService);
        $service->setRenduService($renduService);
        $service->setRoleService($roleService);
        $service->setUrlService($urlService);
        $service->setMacroService($macroService);
        $service->setUserService($userService);
        return $service;
    }
}