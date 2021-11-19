<?php

namespace Formation\Service\Notification;

use Formation\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;

class NotificationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NotificationService
     */
    public function __invoke(ContainerInterface $container) : NotificationService
    {
        /**
         * @var MailService $mailService
         * @var ParametreService $parametreService
         * @var RenduService $renduService
         * @var UrlService $urlService
         */
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $urlService = $container->get(UrlService::class);

        $service = new NotificationService();
        $service->setMailService($mailService);
        $service->setParametreService($parametreService);
        $service->setRenduService($renduService);
        $service->setUrlService($urlService);
        return $service;
    }
}