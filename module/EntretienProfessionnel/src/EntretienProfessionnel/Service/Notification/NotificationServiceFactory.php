<?php

namespace EntretienProfessionnel\Service\Notification;

use Application\Service\Agent\AgentService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
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
         * @var CampagneService $campagneService ;
         * @var MailService $mailService ;
         * @var ParametreService $parametreService ;
         * @var RenduService $renduService
         * @var StructureService $structureService ;
         * @var UrlService $urlService
         */
        $agentService = $container->get(AgentService::class);
        $campagneService = $container->get(CampagneService::class);
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $structureService = $container->get(StructureService::class);
        $urlService = $container->get(UrlService::class);

        $service = new NotificationService();
        $service->setAgentService($agentService);
        $service->setCampagneService($campagneService);
        $service->setMailService($mailService);
        $service->setParametreService($parametreService);
        $service->setRenduService($renduService);
        $service->setStructureService($structureService);
        $service->setUrlService($urlService);
        return $service;
    }
}