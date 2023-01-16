<?php

namespace Formation\Service\Notification;

use Application\Service\Agent\AgentService;
use Application\Service\Macro\MacroService;
use Formation\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
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

        $config = $container->get('Configuration')['formation']['mail'];
        if (isset($config['redirect_to'])) $mailService->setRedirectTo($config['redirect_to']);
        if (isset($config['do_not_send'])) $mailService->setDoNotSend($config['do_not_send']);
        if (isset($config['subject_prefix'])) $mailService->setSubjectPrefix($config['subject_prefix']);
        if (isset($config['from_name'])) $mailService->setFromName($config['from_name']);
        if (isset($config['from_email'])) $mailService->setFromEmail($config['from_email']);

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