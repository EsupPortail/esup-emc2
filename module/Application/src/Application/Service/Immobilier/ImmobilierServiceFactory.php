<?php

namespace Application\Service\Immobilier;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImmobilierServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var ImmobilierService $service */
        $service = new ImmobilierService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}