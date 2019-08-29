<?php

namespace Indicateur\Service\Abonnement;

use Doctrine\ORM\EntityManager;
use Mailing\Service\Mailing\MailingService;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbonnementServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         * @var MailingService $mailingService
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $mailingService = $serviceLocator->get(MailingService::class);

        /** @var AbonnementService $service */
        $service = new AbonnementService();
        $service->setEntityManager($entityManager);
        $service->setMailingService($mailingService);
        return $service;
    }
}