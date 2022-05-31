<?php

namespace Application\Service\Notification;

use Application\Service\Agent\AgentService;
use Application\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;

class NotificationServiceFactory
{

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

        $service = new NotificationService();
        $service->setAgentService($agentService);
        $service->setMailService($mailService);
        $service->setParametreService($parametreService);
        $service->setRenduService($renduService);
        $service->setUrlService($urlService);
        return $service;
    }
}