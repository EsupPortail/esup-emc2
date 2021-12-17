<?php

namespace Application\Service\AgentAccompagnement;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class AgentAccompagnementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentAccompagnementService
     */
    public function __invoke(ContainerInterface $container) : AgentAccompagnementService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentAccompagnementService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}