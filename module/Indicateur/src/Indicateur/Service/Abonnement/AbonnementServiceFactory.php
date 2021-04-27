<?php

namespace Indicateur\Service\Abonnement;

use Doctrine\ORM\EntityManager;
use Indicateur\Service\Indicateur\IndicateurService;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;

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
         * @var MailingService $mailingService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $indicateurService = $container->get(IndicateurService::class);
        $mailingService = $container->get(MailingService::class);

        /** @var AbonnementService $service */
        $service = new AbonnementService();
        $service->setEntityManager($entityManager);
        $service->setIndicateurService($indicateurService);
        $service->setMailingService($mailingService);
        return $service;
    }
}