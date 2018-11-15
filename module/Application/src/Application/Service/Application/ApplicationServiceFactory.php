<?php

namespace Application\Service\Application;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ApplicationService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator) {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var ApplicationService $service */
        $service = new ApplicationService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}