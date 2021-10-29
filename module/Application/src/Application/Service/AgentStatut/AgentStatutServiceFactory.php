<?php

namespace Application\Service\AgentStatut;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class AgentStatutServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentStatutService
     */
    public function __invoke(ContainerInterface $container) : AgentStatutService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentStatutService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}