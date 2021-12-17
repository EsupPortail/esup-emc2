<?php

namespace Formation\Service\FormationInstance;

use Doctrine\ORM\EntityManager;
use Formation\Service\Url\UrlService;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;

class FormationInstanceServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceService
     */
    public function __invoke(ContainerInterface $container) : FormationInstanceService
    {
        /**
         * @var EntityManager $entityManager
         * @var EtatService $etatService
         * @var MailService $mailService
         * @var ParametreService $parametreService
         * @var RenduService $renduService
         * @var UrlService $urlService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $etatService = $container->get(EtatService::class);
        $mailingService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $urlService = $container->get(UrlService::class);

        /**
         * @var FormationInstanceService $service
         */
        $service = new FormationInstanceService();
        $service->setEntityManager($entityManager);
        $service->setEtatService($etatService);
        $service->setMailService($mailingService);
        $service->setParametreService($parametreService);
        $service->setRenduService($renduService);
        $service->setUrlService($urlService);
        return $service;
    }
}