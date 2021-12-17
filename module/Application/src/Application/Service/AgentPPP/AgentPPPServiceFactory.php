<?php

namespace Application\Service\AgentPPP;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class AgentPPPServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentPPPService
     */
    public function __invoke(ContainerInterface $container) : AgentPPPService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentPPPService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}