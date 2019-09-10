<?php

namespace Indicateur\Service\Abonnement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;

class AbonnementServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var MailingService $mailingService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $mailingService = $container->get(MailingService::class);

        /** @var AbonnementService $service */
        $service = new AbonnementService();
        $service->setEntityManager($entityManager);
        $service->setMailingService($mailingService);
        return $service;
    }
}