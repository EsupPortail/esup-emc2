<?php

namespace  Application\Service\RessourceRh;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class RessourceRhServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RessourceRhService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var RessourceRhService $service */
        $service = new RessourceRhService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}