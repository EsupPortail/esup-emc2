<?php

namespace Application\Service\Notification;

use Application\Service\Agent\AgentService;
use Application\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;

class NotificationServiceFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): NotificationService
    {
        /**
         * @var AgentService $agentService ;
         * @var MailService $mailService ;
         * @var ParametreService $parametreService ;
         * @var RenduService $renduService
         * @var UrlService $urlService
         */
        $agentService = $container->get(AgentService::class);
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $urlService = $container->get(UrlService::class);

        $config = $container->get('Configuration')['unicaen-mail'];
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
        $service->setUrlService($urlService);
        return $service;
    }
}