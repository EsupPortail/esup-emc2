<?php

namespace Application\Service\Domaine;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class DomaineServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var DomaineService $service */
        $service = new DomaineService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}