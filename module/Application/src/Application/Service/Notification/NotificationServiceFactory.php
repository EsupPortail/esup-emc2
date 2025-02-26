<?php

namespace Application\Service\Notification;

use Application\Service\Macro\MacroService;
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
         * @var MacroService $macroService ;
         * @var MailService $mailService ;
         * @var ParametreService $parametreService ;
         * @var RenduService $renduService
         * @var UrlService $urlService
         */
        $macroService = $container->get(MacroService::class);
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $urlService = $container->get(UrlService::class);

        $service = new NotificationService();
        $service->setMacroService($macroService);
        $service->setMailService($mailService);
        $service->setParametreService($parametreService);
        $service->setRenduService($renduService);
        $service->setUrlService($urlService);
        return $service;
    }
}