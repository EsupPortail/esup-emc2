<?php

namespace Application\Service\Agent;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class AgentServiceFactory {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AgentService
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator) {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var AgentService $service */
        $service = new AgentService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}