<?php

namespace Formation\Service\Notification;

use Formation\Service\FormationInstance\FormationInstanceService;
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
         * @var FormationInstanceService $formationInstanceService
         * @var MailService $mailService
         * @var ParametreService $parametreService
         * @var RenduService $renduService
         * @var UrlService $urlService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $urlService = $container->get(UrlService::class);

        $service = new NotificationService();
        $service->setFormationInstanceService($formationInstanceService);
        $service->setMailService($mailService);
        $service->setParametreService($parametreService);
        $service->setRenduService($renduService);
        $service->setUrlService($urlService);
        return $service;
    }
}