<?php

namespace Application\Service\AgentQuotite;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class AgentQuotiteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentQuotiteService
     */
    public function __invoke(ContainerInterface $container) : AgentQuotiteService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentQuotiteService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}