<?php

namespace Indicateur\Service\Abonnement;

use Doctrine\ORM\EntityManager;
use Indicateur\Service\Indicateur\IndicateurService;
use Interop\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;

class AbonnementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AbonnementService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var IndicateurService $indicateurService
         * @var MailService $mailService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $indicateurService = $container->get(IndicateurService::class);
        $mailService = $container->get(MailService::class);

        /** @var AbonnementService $service */
        $service = new AbonnementService();
        $service->setEntityManager($entityManager);
        $service->setIndicateurService($indicateurService);
        $service->setMailService($mailService);
        return $service;
    }
}