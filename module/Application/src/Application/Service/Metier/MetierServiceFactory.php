<?php

namespace  Application\Service\Metier;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class MetierServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MetierService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var MetierService $service */
        $service = new MetierService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}