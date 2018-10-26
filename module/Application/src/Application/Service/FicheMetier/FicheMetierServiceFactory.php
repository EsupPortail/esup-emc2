<?php

namespace Application\Service\FicheMetier;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class FicheMetierServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return FicheMetierService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var FicheMetierService $service */
        $service = new FicheMetierService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}