<?php

namespace Application\Service\Poste;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class PosteServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PosteService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var PosteService $service */
        $service = new PosteService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}