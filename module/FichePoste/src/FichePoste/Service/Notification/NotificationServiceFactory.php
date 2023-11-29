<?php

namespace FichePoste\Service\Notification;

use Application\Service\Agent\AgentService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\Url\UrlService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
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
         * @var AgentSuperieurService $agentSuperieurService ;
         * @var MailService $mailService ;
         * @var ParametreService $parametreService ;
         * @var RenduService $renduService
         * @var UrlService $urlService
         */
        $agentService = $container->get(AgentService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $urlService = $container->get(UrlService::class);

        $service = new NotificationService();
        $service->setAgentService($agentService);
        $service->setAgentSuperieurService($agentSuperieurService);
        $service->setMailService($mailService);
        $service->setParametreService($parametreService);
        $service->setRenduService($renduService);
        $service->setUrlService($urlService);
        return $service;
    }
}