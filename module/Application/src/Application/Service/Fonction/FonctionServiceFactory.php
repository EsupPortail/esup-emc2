<?php

namespace Application\Service\Fonction;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class FonctionServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var FonctionService $service */
        $service = new FonctionService();
        $service->setEntityManager($entityManager);
        return $service;

    }
}