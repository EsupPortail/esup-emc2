<?php

namespace Application\Service\FamilleProfessionnelle;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class FamilleProfessionnelleServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var FamilleProfessionnelleService $service */
        $service = new FamilleProfessionnelleService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}