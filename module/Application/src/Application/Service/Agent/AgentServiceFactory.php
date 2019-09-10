<?php

namespace Application\Service\Agent;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class AgentServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentService
     */
    public function __invoke(ContainerInterface $container) {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var AgentService $service */
        $service = new AgentService();
        $service->setEntityManager($entityManager);

        return $service;
    }
}